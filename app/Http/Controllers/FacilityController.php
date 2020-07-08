<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Services\FacilityService;

class FacilityController extends BaseController
{
    public $facility_service;

    public function __construct(FacilityService $facility_service)
    {
        $this->facility_service = $facility_service;
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'contact_number' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'user_status' => 'required',
            'facility_name' => 'required',
            'address' => 'required',
            'region' => 'required',
            'province' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'facility_type' => 'required',
            'facility_status' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
        } else {
            $response = $this->facility_service->create_facility(
                $request->input('first_name'),
                $request->input('last_name'),
                $request->input('contact_number'),
                $request->input('email'),
                $request->input('password'),
                $request->input('user_status'),
                $request->input('facility_name'),
                $request->input('address'),
                $request->input('region'),
                $request->input('province'),
                $request->input('longitude'),
                $request->input('latitude'),
                $request->input('facility_type'),
                $request->input('facility_status')
            );
        }

        return response()->json([
            'success' => $response['success'] ?? 0,
            'errors' => $response['errors'] ?? $errors,
            'data' => $response['data'] ?? []
        ]);
    }

    public function activate(Request $request) {
        $validator = Validator::make($request->all(), [
            'facility_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
        } else {
            $response = $this->facility_service->activate(
                $request->input('facility_id')
            );
        }

        return response()->json([
            'success' => $response['success'] ?? 0,
            'errors' => $response['errors'] ?? $errors,
            'data' => $response['data'] ?? []
        ]);
    }

    public function deactivate(Request $request) {
        $validator = Validator::make($request->all(), [
            'facility_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
        } else {
            $response = $this->facility_service->deactivate(
                $request->input('facility_id')
            );
        }

        return response()->json([
            'success' => $response['success'] ?? 0,
            'errors' => $response['errors'] ?? $errors,
            'data' => $response['data'] ?? []
        ]);
    }

    public function get_facility(Request $request) {
        $validator = Validator::make($request->all(), [
            'facility_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
        } else {
            $response = $this->facility_service->get_facility(
                $request->input('facility_id')
            );
        }

        return response()->json([
            'success' => $response['success'] ?? 0,
            'errors' => $response['errors'] ?? $errors,
            'data' => $response['data'] ?? []
        ]);
    }

    public function get_facilities(Request $request) {
        $response = $this->facility_service->get_facilities();

        return response()->json([
            'success' => $response['success'] ?? 0,
            'errors' => $response['errors'] ?? [],
            'data' => $response['data'] ?? []
        ]);
    }

    public function get_facility_users(Request $request) {
        $validator = Validator::make($request->all(), [
            'facility_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
        } else {
            $response = $this->facility_service->get_facility_users(
                $request->input('facility_id')
            );
        }

        return response()->json([
            'success' => $response['success'] ?? 0,
            'errors' => $response['errors'] ?? $errors,
            'data' => $response['data'] ?? []
        ]);
    }

    public function edit(Request $request) {
        $validator = Validator::make($request->all(), [
            'facility_id' => 'required',
            'facility_name' => 'required',
            'address' => 'required',
            'region' => 'required',
            'province' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'facility_type' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
        } else {
            $response = $this->facility_service->edit(
                $request->input('facility_id'),
                $request->input('facility_name'),
                $request->input('address'),
                $request->input('region'),
                $request->input('province'),
                $request->input('longitude'),
                $request->input('latitude'),
                $request->input('facility_type'),
                $request->input('status')
            );
        }

        return response()->json([
            'success' => $response['success'] ?? 0,
            'errors' => $response['errors'] ?? $errors,
            'data' => $response['data'] ?? []
        ]);
    }
}
