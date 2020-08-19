<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Services\CategoryService;

class Controller extends BaseController
{
    public function index() {
        return view('index');
    }

    public function get_categories(CategoryService $category_service) {
        $response = $category_service->get_categories();

        return response()->json([
            'success' => $response['success'] ?? 0,
            'errors' => $response['errors'] ?? [],
            'data' => $response['data'] ?? []
        ]);
    }
}
