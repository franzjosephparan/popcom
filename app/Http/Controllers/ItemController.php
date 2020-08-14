<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Services\ItemService;

class ItemController extends BaseController
{
    public $item_service;

    public function __construct(ItemService $item_service) {
        $this->item_service = $item_service;
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'item_sku' => 'required',
            'item_name' => 'required',
            'item_description' => 'required',
            'category' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
        } else {
            $response = $this->item_service->create(
                $request->input('item_sku'),
                $request->input('item_name'),
                $request->input('item_description'),
                $request->input('category'),
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
            'item_sku' => 'required',
            'item_name' => 'required',
            'item_description' => 'required',
            'category' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
        } else {
            $response = $this->item_service->edit(
                $request->input('item_id'),
                $request->input('item_sku'),
                $request->input('item_name'),
                $request->input('item_description'),
                $request->input('category'),
                $request->input('image'),
                $request->input('status')
            );
        }

        return response()->json([
            'success' => $response['success'] ?? 0,
            'errors' => $response['errors'] ?? $errors,
            'data' => $response['data'] ?? []
        ]);
    }

    public function search(Request $request) {
        $validator = Validator::make($request->all(), [
            'search' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
        } else {
            $response = $this->item_service->search(
                $request->input('search')
            );
        }

        return response()->json([
            'success' => $response['success'] ?? 0,
            'errors' => $response['errors'] ?? $errors,
            'data' => $response['data'] ?? []
        ]);
    }

    public function get_items() {
        $response = $this->item_service->get_items();

        return response()->json([
            'success' => $response['success'],
            'errors' => $response['errors'],
            'data' => $response['data']
        ]);
    }
}
