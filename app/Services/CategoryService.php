<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Category;

class CategoryService {
    private $authenticated_user;

    public function __construct() {
        $this->authenticated_user = Auth::user();
    }

    public function get_categories() {
        $success = 0;
        $errors = [];
        $data = [];

        try {
            $categories = Category::all();
            $success = 1;
            $data = $categories;
        } catch (\Exception $e) {
            $errors = $e->getMessage();
        }

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }
}
