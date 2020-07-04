<?php
/**
 * @OA\Post(
 *     path="/api/login",
 *     operationId="/api/login",
 *     tags={"User"},
 *
 *     @OA\Parameter(
 *         name="email",
 *         in="query",
 *         description="email",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="password",
 *         in="query",
 *         description="password",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="User details is returned along with the token id",
 *         @OA\JsonContent()
 *     ),
 * )
 */

/**
 * @OA\Post(
 *     path="/api/create-admin",
 *     operationId="/api/create-admin",
 *     tags={"User"},
 *
 *     @OA\Parameter(
 *         name="first_name",
 *         in="query",
 *         description="First name",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="last_name",
 *         in="query",
 *         description="Last name",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="contact_number",
 *         in="query",
 *         description="Contact number",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="email",
 *         in="query",
 *         description="Email",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="password",
 *         in="query",
 *         description="Password",
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
 *         description="Returns id of the created user",
 *         @OA\JsonContent()
 *     ),
 * )
 */

/**
 * @OA\Post(
 *     path="/api/edit-user",
 *     operationId="/api/edit-user",
 *     tags={"User"},
 *
 *     @OA\Parameter(
 *         name="user_id",
 *         in="query",
 *         description="User id",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="first_name",
 *         in="query",
 *         description="First name",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="last_name",
 *         in="query",
 *         description="Last name",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="contact_number",
 *         in="query",
 *         description="Contact number",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="email",
 *         in="query",
 *         description="Email",
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
 *         description="Returns id of the created user",
 *         @OA\JsonContent()
 *     ),
 * )
 */

/**
 * @OA\Post(
 *     path="/api/activate-user",
 *     operationId="/api/activate-user",
 *     tags={"User"},
 *
 *     @OA\Parameter(
 *         name="user_id",
 *         in="query",
 *         description="User id",
 *         required=true,
 *         @OA\Schema(type="integer")
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
 *     path="/api/deavtivate-user",
 *     operationId="/api/deavtivate-user",
 *     tags={"User"},
 *
 *     @OA\Parameter(
 *         name="user_id",
 *         in="query",
 *         description="User id",
 *         required=true,
 *         @OA\Schema(type="integer")
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
 *         description="Returns success and error data",
 *         @OA\JsonContent()
 *     ),
 * )
 */

/**
 * @OA\Post(
 *     path="/api/get-user",
 *     operationId="/api/get-user",
 *     tags={"User"},
 *
 *     @OA\Parameter(
 *         name="user_id",
 *         in="query",
 *         description="User id",
 *         required=true,
 *         @OA\Schema(type="integer")
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
 *         description="Returns the details of a user",
 *         @OA\JsonContent()
 *     ),
 * )
 */

/**
 * @OA\Get(
 *     path="/api/get-users",
 *     operationId="/api/get-users",
 *     tags={"User"},
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
 *         description="Returns the list of all users",
 *         @OA\JsonContent()
 *     ),
 * )
 */
