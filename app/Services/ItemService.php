<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Item;

class ItemService {
    private $authenticated_user;

    public function __construct() {
        $this->authenticated_user = Auth::user();
    }

    public function create(
        $item_sku,
        $item_name,
        $item_description,
        $category,
        $image
    ) {
        $success = 0;
        $errors = [];
        $data = [];

        if (empty($errors)) {
            $item = new Item();
            $item->item_sku = $item_sku;
            $item->item_name = $item_name;
            $item->item_description = $item_description;
            $item->category = $category;

            if (! empty($image)) {
                $extension = explode('/', mime_content_type($image))[1];
                $file_name = Str::random(20) . '.' . $extension;

                if (file_exists(public_path('images'))) {
                    file_put_contents(public_path('images') . '/' . $file_name, file_get_contents($image));
                    $item->image = $file_name;
                }
            }

            $item->status = 1;
            $item->created_by = $this->authenticated_user->id;
            $item->save();

            $success = 1;
            $data = $item;
        }

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }

    public function edit(
        $item_id,
        $item_sku,
        $item_name,
        $item_description,
        $category,
        $image,
        $status,
        $api_token
    ) {
        $success = 0;
        $errors = [];
        $data = [];
        $creator = null;

        $this->check_api_token($api_token, $creator, $errors);

        if (empty($errors)) {
            $item = Item::find($item_id);

            if (! empty($item)) {
                $item->item_sku = $item_sku;
                $item->item_name = $item_name;
                $item->item_description = $item_description;
                $item->category = $category;

                if (!empty($image)) {
                    $extension = explode('/', mime_content_type($image))[1];
                    $file_name = Str::random(20) . '.' . $extension;
                    file_put_contents(public_path('uploads') . '/' . $file_name, file_get_contents($image));

                    $item->image = $file_name;
                }

                $item->status = $status;
                $item->updated_by = $creator->id;
                $item->save();

                $success = 1;
                $data = $item;
            } else {
                $errors = 'item does not exist';
            }
        }

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }

    public function get_items() {
        $success = 0;
        $errors = [];
        $data = [];
        $creator = null;

        try {
            $items = Item::where('status', 1)->get();
            $success = 1;
            $data = $items;
        } catch (\Exception $ex) {
            $errors = 'unexpected error';
        }

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }

    public function search($search) {
        $items = Item::where([
            ['item_sku', 'like', '%' . $search . '%'],
            ['status', 1]
        ])->orWhere([
            ['item_name', 'like', '%' . $search . '%'],
            ['status', 1]
        ])->get();

        return [
            'success' => 1,
            'errors' => 0,
            'data' => $items
        ];
    }
}
