<?php
/**
 * @OA\Post(
 *     path="/api/create-facility",
 *     operationId="/api/create-facility",
 *     tags={"Facility"},
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
 *         name="user_status",
 *         in="query",
 *         description="User status",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="facility_name",
 *         in="query",
 *         description="Facility name",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="address",
 *         in="query",
 *         description="address",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="region",
 *         in="query",
 *         description="Region",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="province",
 *         in="query",
 *         description="Province",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="longitude",
 *         in="query",
 *         description="Longitude",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="latitude",
 *         in="query",
 *         description="Latitude",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="facility_type",
 *         in="query",
 *         description="Facility type",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="facility_status",
 *         in="query",
 *         description="Facility status",
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
 *         description="User details is returned along with the token id",
 *         @OA\JsonContent()
 *     ),
 * )
 */

/**
 * @OA\Post(
 *     path="/api/activate-facility",
 *     operationId="/api/activate-facility",
 *     tags={"Facility"},
 *
 *     @OA\Parameter(
 *         name="facility_id",
 *         in="query",
 *         description="Facility ID",
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
 *         description="",
 *         @OA\JsonContent()
 *     ),
 * )
 */

/**
 * @OA\Post(
 *     path="/api/deactivate-facility",
 *     operationId="/api/deactivate-facility",
 *     tags={"Facility"},
 *
 *     @OA\Parameter(
 *         name="facility_id",
 *         in="query",
 *         description="Facility ID",
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
 *         description="",
 *         @OA\JsonContent()
 *     ),
 * )
 */

/**
 * @OA\Post(
 *     path="/api/get-facility",
 *     operationId="/api/get-facility",
 *     tags={"Facility"},
 *
 *     @OA\Parameter(
 *         name="facility_id",
 *         in="query",
 *         description="Facility ID",
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
 *         description="",
 *         @OA\JsonContent()
 *     ),
 * )
 */

/**
 * @OA\Post(
 *     path="/api/get-facilities",
 *     operationId="/api/get-facilities",
 *     tags={"Facility"},
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
 *         description="",
 *         @OA\JsonContent()
 *     ),
 * )
 */

/**
 * @OA\Post(
 *     path="/api/get-facility-user",
 *     operationId="/api/get-facility-user",
 *     tags={"Facility"},
 *
 *     @OA\Parameter(
 *         name="facility_id",
 *         in="query",
 *         description="Facility ID",
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
 *         description="",
 *         @OA\JsonContent()
 *     ),
 * )
 */
