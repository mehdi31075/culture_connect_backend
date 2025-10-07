<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="CultureConnect API",
 *     version="1.0.0",
 *     description="API documentation for CultureConnect application - A cultural events and experiences platform",
 *     @OA\Contact(
 *         email="support@cultureconnect.com"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter JWT Bearer token"
 * )
 *
 * @OA\Tag(
 *     name="Authentication",
 *     description="Authentication endpoints"
 * )
 *
 * @OA\Tag(
 *     name="User",
 *     description="User management endpoints"
 * )
 *
 * @OA\Tag(
 *     name="Events",
 *     description="Event management endpoints"
 * )
 *
 * @OA\Tag(
 *     name="Pavilions",
 *     description="Pavilion management endpoints"
 * )
 *
 * @OA\Tag(
 *     name="Shops",
 *     description="Shop management endpoints"
 * )
 *
 * @OA\Tag(
 *     name="Products",
 *     description="Product management endpoints"
 * )
 *
 * @OA\Tag(
 *     name="Orders",
 *     description="Order management endpoints"
 * )
 *
 * @OA\Tag(
 *     name="Reviews",
 *     description="Review management endpoints"
 * )
 *
 * @OA\Tag(
 *     name="Notifications",
 *     description="Notification management endpoints"
 * )
 *
 * @OA\Tag(
 *     name="Wallet",
 *     description="Wallet and rewards endpoints"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
