<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Services\UserService;
use Illuminate\Support\Facades\Validator;

class UsersController extends BaseController
{
    public $user_service;

    public function __construct(UserService $user_service)
    {
        $this->user_service = $user_service;
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
        } else {
            $response = $this->user_service->login(
                $request->input('email'),
                $request->input('password')
            );
        }

        return response()->json([
            'success' => $response['success'] ?? 0,
            'errors' => $response['errors'] ?? $errors,
            'data' => $response['data'] ?? []
        ]);
    }

    public function create_admin(Request $request) {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'contact_number' => 'required',
            'email' => 'required|unique:users,email|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
        } else {
            $response = $this->user_service->create_admin(
                $request->input('first_name'),
                $request->input('last_name'),
                $request->input('contact_number'),
                $request->input('email'),
                $request->input('password'),
                $request->input('image')
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
            'user_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'contact_number' => 'required',
            'email' => 'required|email',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
        } else {
            $response = $this->user_service->edit(
                $request->input('user_id'),
                $request->input('first_name'),
                $request->input('last_name'),
                $request->input('contact_number'),
                $request->input('email'),
                $request->input('password'),
                $request->input('status')
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
            'user_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
        } else {
            $response = $this->user_service->activate_user(
                $request->input('user_id')
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
            'user_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
        } else {
            $response = $this->user_service->deactivate_user(
                $request->input('user_id'),
                $request->input('api_token')
            );
        }

        return response()->json([
            'success' => $response['success'] ?? 0,
            'errors' => $response['errors'] ?? $errors,
            'data' => $response['data'] ?? []
        ]);
    }

    public function get_user(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
        } else {
            $response = $this->user_service->get_user(
                $request->input('user_id')
            );
        }

        return response()->json([
            'success' => $response['success'] ?? 0,
            'errors' => $response['errors'] ?? $errors,
            'data' => $response['data'] ?? []
        ]);
    }

    public function get_users() {
        $response = $this->user_service->get_users();

        return response()->json([
            'success' => $response['success'],
            'errors' => $response['errors'],
            'data' => $response['data']
        ]);
    }

    public function get_user_facilities(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
        } else {
            $response = $this->user_service->get_user_facilities(
                $request->input('user_id')
            );
        }

        return response()->json([
            'success' => $response['success'] ?? 0,
            'errors' => $response['errors'] ?? $errors,
            'data' => $response['data']
        ]);
    }

    public function add_user_to_facility(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'facility_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
        } else {
            $response = $this->user_service->add_user_to_facility(
                $request->input('user_id'),
                $request->input('facility_id')
            );
        }

        return response()->json([
            'success' => $response['success'] ?? 0,
            'errors' => $response['errors'] ?? $errors,
            'data' => $response['data']
        ]);
    }
}
