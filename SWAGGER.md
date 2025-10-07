# 📚 Swagger API Documentation

## 🌐 **Access Swagger UI**

-   **Development**: http://localhost:8000/api/swagger
-   **Production**: https://yourdomain.com/api/swagger

## 🔧 **Troubleshooting**

### **CORS/Network Issues**

If you see "Failed to fetch" or CORS errors in Swagger UI:

1. **Check the host configuration** in `config/l5-swagger.php`:

    ```php
    'constants' => [
        'L5_SWAGGER_CONST_HOST' => env('L5_SWAGGER_CONST_HOST', 'http://localhost:8000'),
    ],
    ```

2. **Set environment variable** (optional):

    ```bash
    L5_SWAGGER_CONST_HOST=http://localhost:8000
    ```

3. **Regenerate documentation**:
    ```bash
    php artisan l5-swagger:generate
    ```

### **Common Issues**

-   **"my-default-host.com" errors**: The host constant is not set correctly
-   **CORS errors**: Make sure the host matches your actual server URL
-   **404 errors**: Ensure the API routes are properly registered

## 🔧 **Swagger Configuration**

The API documentation is generated using the `darkaonline/l5-swagger` package with comprehensive annotations.

### **Main Configuration Files:**

-   `config/l5-swagger.php` - Swagger package configuration
-   `app/Http/Controllers/Controller.php` - Global API information and security schemes
-   `app/Http/Controllers/Api/AuthController.php` - Authentication endpoints documentation
-   `routes/api.php` - API routes with Swagger annotations

## 📋 **Documented Endpoints**

### **Authentication Endpoints**

-   `POST /api/auth/request-otp` - Request OTP for phone/email
-   `POST /api/auth/verify-otp` - Verify OTP and get JWT tokens
-   `POST /api/auth/google` - Google OAuth authentication
-   `POST /api/auth/refresh` - Refresh JWT token
-   `POST /api/auth/logout` - Logout and invalidate token

### **User Endpoints**

-   `GET /api/user` - Get authenticated user information
-   `GET /api/profile` - Get user profile

## 🔐 **Authentication**

The API uses JWT Bearer token authentication. To use protected endpoints:

1. **Get a token** by calling `/api/auth/verify-otp` or `/api/auth/google`
2. **Include the token** in the Authorization header:
    ```
    Authorization: Bearer your_jwt_token_here
    ```
3. **Use the token** in Swagger UI by clicking the "Authorize" button

## 🛠️ **Regenerating Documentation**

When you add new endpoints or modify existing ones:

```bash
# Generate/regenerate Swagger documentation
php artisan l5-swagger:generate

# Clear cache if needed
php artisan config:clear
php artisan route:clear
```

## 📝 **Adding New Endpoints**

To document new API endpoints, add Swagger annotations to your controllers:

```php
/**
 * @OA\Post(
 *     path="/api/your-endpoint",
 *     summary="Your endpoint summary",
 *     description="Detailed description of what this endpoint does",
 *     tags={"YourTag"},
 *     security={{"bearerAuth":{}}}, // If authentication required
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"field1","field2"},
 *             @OA\Property(property="field1", type="string", example="value1"),
 *             @OA\Property(property="field2", type="integer", example=123)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Success response",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="data", type="object")
 *         )
 *     )
 * )
 */
public function yourMethod(Request $request)
{
    // Your implementation
}
```

## 🏷️ **Available Tags**

-   **Authentication** - Login, logout, token management
-   **User** - User profile and information
-   **Events** - Event management (to be added)
-   **Pavilions** - Pavilion management (to be added)
-   **Shops** - Shop management (to be added)
-   **Products** - Product management (to be added)
-   **Orders** - Order management (to be added)
-   **Reviews** - Review management (to be added)
-   **Notifications** - Notification management (to be added)
-   **Wallet** - Wallet and rewards (to be added)

## 🔄 **API Versioning**

The current API version is **1.0.0**. When making breaking changes:

1. Update the version in `app/Http/Controllers/Controller.php`
2. Consider implementing API versioning in routes
3. Update the Swagger documentation accordingly

## 🚀 **Production Considerations**

-   **Security**: Swagger UI should be disabled in production or restricted to admin users
-   **Performance**: Consider caching the generated documentation
-   **Updates**: Regenerate documentation after each deployment

## 📖 **Swagger/OpenAPI Resources**

-   [OpenAPI Specification](https://swagger.io/specification/)
-   [L5-Swagger Package](https://github.com/DarkaOnLine/L5-Swagger)
-   [Swagger UI](https://swagger.io/tools/swagger-ui/)

Your API documentation is now fully integrated and ready for use! 🎉
