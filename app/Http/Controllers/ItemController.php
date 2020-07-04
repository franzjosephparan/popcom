<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Services\ItemService;

class ItemController extends BaseController
{
    public $item_service;

    public function __construct(ItemService $item_service) {
        $this->item_service = $item_service;
    }

    public function create(Request $request) {
        $this->validate($request, [
            'item_sku' => 'required',
            'item_name' => 'required',
            'item_description' => 'required',
            'category' => 'required',
            'status' => 'required',
            'api_token' => 'required'
        ]);

        $response = $this->item_service->create(
            $request->input('item_sku'),
            $request->input('item_name'),
            $request->input('item_description'),
            $request->input('category'),
            $request->input('image'),
            $request->input('status'),
            $request->input('api_token')
        );

        return response()->json([
            'success' => $response['success'],
            'errors' => $response['errors'],
            'data' => $response['data']
        ]);
    }

    public function edit(Request $request) {
        $this->validate($request, [
            'item_sku' => 'required',
            'item_name' => 'required',
            'item_description' => 'required',
            'category' => 'required',
            'status' => 'required'
        ]);

        $response = $this->item_service->edit(
            $request->input('item_id'),
            $request->input('item_sku'),
            $request->input('item_name'),
            $request->input('item_description'),
            $request->input('category'),
            $request->input('image'),
            $request->input('status'),
            $request->input('api_token')
        );

        return response()->json([
            'success' => $response['success'],
            'errors' => $response['errors'],
            'data' => $response['data']
        ]);
    }

    public function search(Request $request) {
        $this->validate($request, [
            'search' => 'required'
        ]);

        $response = $this->item_service->search(
            $request->input('search')
        );

        return response()->json([
            'success' => $response['success'],
            'errors' => $response['errors'],
            'data' => $response['data']
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
