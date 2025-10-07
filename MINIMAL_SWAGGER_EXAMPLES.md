# 🎯 Minimal Swagger Annotations

## 📝 **Current vs Minimal Approach**

### **❌ Current (Verbose):**

```php
/**
 * @OA\Post(
 *     path="/api/auth/request-otp",
 *     summary="Request OTP for authentication",
 *     description="Send an OTP code to the provided phone number or email address",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"provider","identifier"},
 *             @OA\Property(property="provider", type="string", enum={"phone", "email"}, example="phone", description="Authentication provider"),
 *             @OA\Property(property="identifier", type="string", example="+1234567890", description="Phone number or email address")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OTP sent successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="OTP sent successfully"),
 *             @OA\Property(property="otp", type="string", example="123456", description="OTP code (only in development)")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Validation failed"),
 *             @OA\Property(property="errors", type="object")
 *         )
 *     )
 * )
 */
```

### **✅ Minimal (Clean):**

```php
/**
 * @OA\Post(
 *     path="/api/auth/request-otp",
 *     summary="Request OTP",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             @OA\Property(property="provider", type="string", enum={"phone", "email"}),
 *             @OA\Property(property="identifier", type="string", example="+1234567890")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Success")
 * )
 */
```

## 🎯 **Minimal Patterns for Common Endpoints**

### **1. Simple GET Endpoint:**

```php
/**
 * @OA\Get(
 *     path="/api/status",
 *     summary="API Status",
 *     @OA\Response(response=200, description="OK")
 * )
 */
```

### **2. POST with Enum Dropdown:**

```php
/**
 * @OA\Post(
 *     path="/api/auth/login",
 *     summary="Login",
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             @OA\Property(property="type", type="string", enum={"email", "phone"}),
 *             @OA\Property(property="value", type="string")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Success")
 * )
 */
```

### **3. Protected Endpoint:**

```php
/**
 * @OA\Get(
 *     path="/api/user",
 *     summary="Get User",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(response=200, description="User data")
 * )
 */
```

### **4. POST with Validation:**

```php
/**
 * @OA\Post(
 *     path="/api/orders",
 *     summary="Create Order",
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", enum={"pending", "confirmed", "cancelled"}),
 *             @OA\Property(property="amount", type="number", example=99.99)
 *         )
 *     ),
 *     @OA\Response(response=201, description="Created")
 * )
 */
```

## 🧹 **What You Can Remove:**

### **Remove These (Optional):**

-   `description` (if summary is enough)
-   `required=true` (if obvious)
-   `example` values (if not needed)
-   Multiple `@OA\Response` (keep only success)
-   Detailed `@OA\JsonContent` (keep simple)

### **Keep These (Essential):**

-   `path` - Endpoint URL
-   `summary` - Short description
-   `@OA\Property` with `enum` - For dropdowns
-   `security` - For protected endpoints
-   Basic `@OA\Response` - For success

## 🎯 **Result:**

With minimal annotations, you get:

-   ✅ **Swagger UI** at `/api/swagger`
-   ✅ **Enum dropdowns** for testing
-   ✅ **Interactive forms**
-   ✅ **Much less code** to write
-   ✅ **Easier to maintain**

## 💡 **Recommendation:**

Start with **minimal annotations** and add more details only if needed. The goal is to have working Swagger UI for API testing, not perfect documentation.
