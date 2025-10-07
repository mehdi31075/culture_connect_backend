<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuthMethod;
use App\Models\OtpCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\OtpRequest;
use App\Http\Requests\VerifyOtpRequest;

/**
 * @OA\Info(
 *     title="CultureConnect API",
 *     version="1.0.0",
 *     description="API documentation for CultureConnect application"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter JWT token"
 * )
 *
 * @OA\Tag(
 *     name="Authentication",
 *     description="Authentication endpoints for OTP and Google login"
 * )
 */
class AuthController extends Controller
{
    /**
     * Parse Accept-Language header to extract only the primary locale
     */
    private function parseLocale($acceptLanguage)
    {
        if (empty($acceptLanguage)) {
            return 'en';
        }

        // Split by comma and take the first part
        $locales = explode(',', $acceptLanguage);
        $primaryLocale = trim($locales[0]);

        // Extract only the language-country part (e.g., "en-US" from "en-US,en;q=0.9")
        $parts = explode(';', $primaryLocale);
        return trim($parts[0]);
    }
    /**
     * @OA\Schema(
     *     schema="ProviderEnum",
     *     type="string",
     *     enum={"phone", "email"},
     *     description="Authentication provider"
     * )
     * @OA\Post(
     *     path="/api/auth/request-otp",
     *     summary="Request OTP",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"provider", "value"},
     *             @OA\Property(
     *                 property="provider",
     *                 ref="#/components/schemas/ProviderEnum"
     *             ),
     *             @OA\Property(property="value", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function requestOtp(OtpRequest $request)
    {
        $provider = $request->provider;
        $identifier = $request->value;

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Find or create auth method
        $authMethod = AuthMethod::firstOrCreate(
            ['provider' => $provider, 'identifier' => $identifier],
            ['user_id' => null, 'verified_at' => null]
        );

        // Create OTP code
        OtpCode::create([
            'auth_method_id' => $authMethod->id,
            'code' => $otp,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        // In a real app, you would send the OTP via SMS/Email here
        // For now, we'll just return it in the response for testing
        return response()->json([
            'success' => true,
            'message' => __('messages.otp_sent'),
            'otp' => $otp, // Remove this in production
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/verify-otp",
     *     summary="Verify OTP",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"provider", "value", "code"},
     *             @OA\Property(
     *                 property="provider",
     *                 ref="#/components/schemas/ProviderEnum"
     *             ),
     *             @OA\Property(property="value", type="string"),
     *             @OA\Property(property="code", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function verifyOtp(VerifyOtpRequest $request)
    {
        $provider = $request->provider;
        $identifier = $request->value;
        $code = $request->code;

        // Find auth method
        $authMethod = AuthMethod::where('provider', $provider)
            ->where('identifier', $identifier)
            ->first();

        if (!$authMethod) {
            return response()->json([
                'success' => false,
                'message' => __('messages.auth_method_not_found'),
            ], 404);
        }

        // Find valid OTP
        $otpCode = OtpCode::where('auth_method_id', $authMethod->id)
            ->where('code', $code)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otpCode) {
            return response()->json([
                'success' => false,
                'message' => __('messages.invalid_code'),
            ], 400);
        }

        // Mark OTP as used
        $otpCode->update(['is_used' => true]);

        // Find or create user
        $user = $authMethod->user;
        $signedUpBefore = $user !== null;

        if (!$user) {
            $user = User::create([
                'email' => $provider === 'email' ? $identifier : null,
                'phone' => $provider === 'phone' ? $identifier : null,
                'first_name' => '',
                'last_name' => '',
                'locale' => $this->parseLocale($request->header('Accept-Language', 'en')),
            ]);

            // Create profile
            $user->profile()->create([
                'preferences_json' => [],
            ]);

            // Create wallet
            $user->wallet()->create([
                'points' => 0,
                'tier' => 'bronze',
            ]);

            // Update auth method with user
            $authMethod->update([
                'user_id' => $user->id,
                'verified_at' => Carbon::now(),
            ]);
        } else {
            // Update verification time
            $authMethod->update(['verified_at' => Carbon::now()]);
        }

        // Generate JWT tokens
        try {
            $accessToken = JWTAuth::fromUser($user);
            $refreshToken = JWTAuth::fromUser($user, ['type' => 'refresh']);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not create token',
            ], 500);
        }

        // Calculate remaining time in milliseconds
        $accessRemainingMs = config('jwt.ttl') * 60 * 1000; // Convert minutes to milliseconds
        $refreshRemainingMs = config('jwt.refresh_ttl') * 60 * 1000; // Convert minutes to milliseconds

        return response()->json([
            'success' => true,
            'message' => 'Authentication successful',
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'access_remaining_ms' => $accessRemainingMs,
            'refresh_remaining_ms' => $refreshRemainingMs,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'phone' => $user->phone,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'sex' => $user->sex,
                'birthday' => $user->birthday,
                'locale' => $user->locale,
                'is_active' => $user->is_active,
                'created_at' => $user->created_at,
            ],
            'is_registered' => $signedUpBefore,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/google",
     *     summary="Google Login",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="id_token", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success")
     * )
     */
    public function googleLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Verify Google ID token
            $client = new \Google_Client();
            $client->setClientId(config('services.google.client_id'));
            $payload = $client->verifyIdToken($request->id_token);

            if (!$payload) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Google token',
                ], 400);
            }

            $googleUser = $payload;
            $email = $googleUser['email'];

            // Find or create auth method
            $authMethod = AuthMethod::firstOrCreate(
                ['provider' => 'google', 'identifier' => $email],
                ['user_id' => null, 'verified_at' => null]
            );

            // Find or create user
            $user = $authMethod->user;
            $signedUpBefore = $user !== null;

            if (!$user) {
                $user = User::create([
                    'email' => $email,
                    'first_name' => $googleUser['given_name'] ?? '',
                    'last_name' => $googleUser['family_name'] ?? '',
                    'locale' => $this->parseLocale($request->header('Accept-Language', 'en')),
                ]);

                // Create profile
                $user->profile()->create([
                    'avatar_url' => $googleUser['picture'] ?? null,
                    'preferences_json' => [],
                ]);

                // Create wallet
                $user->wallet()->create([
                    'points' => 0,
                    'tier' => 'bronze',
                ]);

                // Update auth method
                $authMethod->update([
                    'user_id' => $user->id,
                    'verified_at' => Carbon::now(),
                ]);
            } else {
                // Update verification time
                $authMethod->update(['verified_at' => Carbon::now()]);
            }

            // Generate JWT tokens
            $accessToken = JWTAuth::fromUser($user);
            $refreshToken = JWTAuth::fromUser($user, ['type' => 'refresh']);

            // Calculate remaining time in milliseconds
            $accessRemainingMs = config('jwt.ttl') * 60 * 1000; // Convert minutes to milliseconds
            $refreshRemainingMs = config('jwt.refresh_ttl') * 60 * 1000; // Convert minutes to milliseconds

            return response()->json([
                'success' => true,
                'message' => 'Google authentication successful',
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'access_remaining_ms' => $accessRemainingMs,
                'refresh_remaining_ms' => $refreshRemainingMs,
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'sex' => $user->sex,
                    'birthday' => $user->birthday,
                    'locale' => $user->locale,
                    'is_active' => $user->is_active,
                    'created_at' => $user->created_at,
                ],
                'is_registered' => $signedUpBefore,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Google authentication failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/auth/refresh",
     *     summary="Refresh Token",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success")
     * )
     */
    public function refresh(Request $request)
    {
        try {
            $token = JWTAuth::refresh($request->token);

            return response()->json([
                'success' => true,
                'access_token' => $token,
                'access_expires_at_ms' => Carbon::now()->addMinutes(config('jwt.ttl'))->timestamp * 1000,
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token refresh failed',
            ], 401);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     summary="Logout",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success")
     * )
     */
    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out',
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout failed',
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/auth/profile",
     *     summary="Get user profile",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User profile retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Profile retrieved successfully"),
     *             @OA\Property(
     *                 property="profile",
     *                 type="object",
     *                 @OA\Property(property="first_name", type="string", example="John"),
     *                 @OA\Property(property="last_name", type="string", example="Doe"),
     *                 @OA\Property(property="sex", type="string", enum={"male", "female"}, example="male"),
     *                 @OA\Property(property="birthday", type="string", format="date", example="1990-01-01"),
     *                 @OA\Property(property="nationality", type="string", example="US")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function getProfile(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                ], 401);
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile retrieved successfully',
                'profile' => [
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'sex' => $user->sex,
                    'birthday' => $user->birthday,
                    'nationality' => $user->nationality,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve profile',
            ], 500);
        }
    }
}
