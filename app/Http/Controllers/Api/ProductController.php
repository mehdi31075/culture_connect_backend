<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Product",
 *     description="Product items management endpoints"
 * )
 */
class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Get all products",
     *     description="Retrieve a list of all products with optional filtering.",
     *     operationId="getProducts",
     *     tags={"Product"},
     *     @OA\Parameter(
     *         name="shop_id",
     *         in="query",
     *         description="Filter by shop ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="tag",
     *         in="query",
     *         description="Filter by tag ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="trending",
     *         in="query",
     *         description="Filter trending items only",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         name="available",
     *         in="query",
     *         description="Filter available items only",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search term for name or description",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Products retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Products retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="shop_id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Viral TikTok Shawarma"),
     *                     @OA\Property(property="description", type="string", example="The shawarma that broke the internet!"),
     *                     @OA\Property(property="price", type="number", format="float", example=18.00),
     *                     @OA\Property(property="discounted_price", type="number", format="float", nullable=true, example=15.00),
     *                     @OA\Property(
     *                         property="images",
     *                         type="array",
     *                         @OA\Items(type="string", example="https://example.com/storage/products/product1.jpg")
     *                     ),
     *                     @OA\Property(property="views_count", type="integer", example=89300),
     *                     @OA\Property(property="is_trending", type="boolean", example=true),
     *                     @OA\Property(property="trending_position", type="integer", nullable=true, example=1),
     *                     @OA\Property(property="trending_score", type="number", format="float", nullable=true, example=98.00),
     *                     @OA\Property(property="preparation_time", type="string", nullable=true, example="5-8 mins"),
     *                     @OA\Property(property="is_available", type="boolean", example=true),
     *                     @OA\Property(property="average_rating", type="number", format="float", example=4.5),
     *                     @OA\Property(property="reviews_count", type="integer", example=2847),
     *                     @OA\Property(property="shop", type="object"),
     *                     @OA\Property(property="tags", type="array", @OA\Items(type="object"))
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $shopId = $request->get('shop_id');
            $tagId = $request->get('tag');
            $trending = $request->get('trending');
            $available = $request->get('available');
            $search = $request->get('search');

            $query = Product::with(['shop', 'tags']);

            // Filter by shop
            if ($shopId) {
                $query->where('shop_id', $shopId);
            }

            // Filter by tag
            if ($tagId) {
                $query->whereHas('tags', function ($q) use ($tagId) {
                    $q->where('product_tags.tag_id', $tagId);
                });
            }

            // Filter trending
            if ($trending !== null) {
                $query->where('is_trending', filter_var($trending, FILTER_VALIDATE_BOOLEAN));
            }

            // Filter available
            if ($available !== null) {
                $query->where('is_available', filter_var($available, FILTER_VALIDATE_BOOLEAN));
            }

            // Search filter
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Order by trending position if trending, otherwise by name
            if ($trending) {
                $query->orderBy('trending_position', 'asc')
                      ->orderBy('trending_score', 'desc');
            } else {
                $query->orderBy('name', 'asc');
            }

            $products = $query->get();

            // Increment views and add calculated fields for all products
            $products->each(function ($product) {
                $product->incrementViews(); // Auto-increment views on fetch
                $product->average_rating = $product->average_rating;
                $product->reviews_count = $product->reviews_count;
            });

            return response()->json([
                'success' => true,
                'message' => 'Products retrieved successfully',
                'data' => $products,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve products',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/shops/{shop}/products",
     *     summary="Get all products for a shop",
     *     description="Retrieve all products for a specific shop.",
     *     operationId="getShopProducts",
     *     tags={"Product"},
     *     @OA\Parameter(
     *         name="shop",
     *         in="path",
     *         description="Shop ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Products retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Products retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="shop_id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Viral TikTok Shawarma"),
     *                     @OA\Property(property="description", type="string", example="The shawarma that broke the internet!"),
     *                     @OA\Property(property="price", type="number", format="float", example=18.00),
     *                     @OA\Property(property="is_food", type="boolean", example=true),
     *                     @OA\Property(
     *                         property="images",
     *                         type="array",
     *                         @OA\Items(type="string", example="https://example.com/storage/products/product1.jpg")
     *                     ),
     *                     @OA\Property(property="views_count", type="integer", example=89300),
     *                     @OA\Property(property="is_trending", type="boolean", example=true),
     *                     @OA\Property(property="trending_position", type="integer", nullable=true, example=1),
     *                     @OA\Property(property="trending_score", type="number", format="float", nullable=true, example=98.00),
     *                     @OA\Property(property="preparation_time", type="string", nullable=true, example="5-8 mins"),
     *                     @OA\Property(property="is_available", type="boolean", example=true),
     *                     @OA\Property(property="average_rating", type="number", format="float", example=4.5),
     *                     @OA\Property(property="reviews_count", type="integer", example=2847),
     *                     @OA\Property(property="shop", type="object"),
     *                     @OA\Property(property="tags", type="array", @OA\Items(type="object"))
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Shop not found"
     *     )
     * )
     */
    public function shopProducts(Request $request, int $shopId): JsonResponse
    {
        try {
            $shop = Shop::find($shopId);

            if (!$shop) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shop not found',
                ], 404);
            }

            $products = Product::where('shop_id', $shopId)
                ->with(['tags'])
                ->orderBy('is_trending', 'desc')
                ->orderBy('trending_position', 'asc')
                ->orderBy('name', 'asc')
                ->get();

            // Increment views and add calculated fields for all products
            $products->each(function ($product) {
                $product->incrementViews(); // Auto-increment views on fetch
                $product->average_rating = $product->average_rating;
                $product->reviews_count = $product->reviews_count;
            });

            return response()->json([
                'success' => true,
                'message' => 'Products retrieved successfully',
                'data' => $products,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve products',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     summary="Get product by ID",
     *     description="Retrieve a specific product item by its ID",
     *     operationId="getProductById",
     *     tags={"Product"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Product ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Product retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="shop_id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Viral TikTok Shawarma"),
     *                 @OA\Property(property="description", type="string", example="The shawarma that broke the internet!"),
     *                 @OA\Property(property="price", type="number", format="float", example=18.00),
     *                 @OA\Property(property="discounted_price", type="number", format="float", nullable=true, example=15.00),
     *                 @OA\Property(
     *                     property="images",
     *                     type="array",
     *                     @OA\Items(type="string", example="https://example.com/storage/products/product1.jpg")
     *                 ),
     *                 @OA\Property(property="views_count", type="integer", example=89300),
     *                 @OA\Property(property="is_trending", type="boolean", example=true),
     *                 @OA\Property(property="trending_position", type="integer", nullable=true, example=1),
     *                 @OA\Property(property="trending_score", type="number", format="float", nullable=true, example=98.00),
     *                 @OA\Property(property="preparation_time", type="string", nullable=true, example="5-8 mins"),
     *                 @OA\Property(property="is_available", type="boolean", example=true),
     *                 @OA\Property(property="average_rating", type="number", format="float", example=4.5),
     *                 @OA\Property(property="reviews_count", type="integer", example=2847),
     *                 @OA\Property(property="shop", type="object"),
     *                 @OA\Property(property="tags", type="array", @OA\Items(type="object"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $product = Product::with(['shop', 'tags'])->find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                ], 404);
            }

            // Increment views and add calculated fields
            $product->incrementViews(); // Auto-increment views on fetch
            $product->average_rating = $product->average_rating;
            $product->reviews_count = $product->reviews_count;

            return response()->json([
                'success' => true,
                'message' => 'Product retrieved successfully',
                'data' => $product,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve product',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

