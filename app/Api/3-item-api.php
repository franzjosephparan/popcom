<?php
/**
 * @OA\Post(
 *     path="/api/create-item",
 *     operationId="/api/create-item",
 *     tags={"Item"},
 *
 *     @OA\Parameter(
 *         name="item_sku",
 *         in="query",
 *         description="Item SKU",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="item_name",
 *         in="query",
 *         description="Item name",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="item_description",
 *         in="query",
 *         description="Item description",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="category",
 *         in="query",
 *         description="Category",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="image",
 *         in="query",
 *         description="Image",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="status",
 *         in="query",
 *         description="Status",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="api_token",
 *         in="query",
 *         description="Api token",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Returns success and errors data",
 *         @OA\JsonContent()
 *     ),
 * )
 */

/**
 * @OA\Post(
 *     path="/api/get-items",
 *     operationId="/api/get-items",
 *     tags={"Item"},
 *
 *     @OA\Parameter(
 *         name="api_token",
 *         in="query",
 *         description="Api token",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Returns success and errors data",
 *         @OA\JsonContent()
 *     ),
 * )
 */
