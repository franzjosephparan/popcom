<?php
namespace App\Services;

use App\InventoryTransfer;
use Illuminate\Support\Facades\Auth;
use App\BatchInventory;
use App\Item;
use App\InventoryRequest;
use App\InventoryRequestLine;
use Illuminate\Support\Facades\DB;

class BatchService {
    private $authenticated_user;

    public function __construct() {
        $this->authenticated_user = Auth::user();
    }

    public function add_starting_inventory(
        $batch_name,
        $facility_id,
        $items
    ) {
        $success = 0;
        $errors = [];
        $data = [];

        try {
            $batch_data = [];
            foreach ($items as $item) {
                $batch = new BatchInventory();
                $batch->batch_name = $batch_name;
                $batch->facility_id = $facility_id;
                $batch->item_id = $item['item_id'];
                $batch->quantity = $item['quantity'];
                $batch->uom = $item['uom'];
                $batch->expiration_date = $item['expiration_date'];
                $batch->status = 1;
                $batch->created_by = $this->authenticated_user->id;
                $batch->save();
                array_push($batch_data, $batch->toArray());
            }

            $success = 1;
            $data = $batch_data;
        } catch (\Exception $ex) {
            $errors = 'An error occurred';
        }

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }

    public function adjust_inventory($batch_id, $quantity) {
        $success = 0;
        $errors = [];
        $data = [];

        try {
            $batch = BatchInventory::find($batch_id);
            $batch->quantity = $quantity;
            $batch->updated_by = $this->authenticated_user->id;
            $batch->save();

            $data = $batch;
            $success = 1;
        } catch (\Exception $ex) {
            $errors = 'An error occurred';
        }

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }

    public function get_inventory($facility_id) {
        $success = 0;
        $errors = [];
        $data = [];

        try {
            $items = Item::with('batch')->where('status', 1)->get();
            $data = $items;
            $success = 1;
        } catch (\Exception $ex) {
            $errors = $ex->getMessage();
        }

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }

    public function get_batch($batch_id) {
        $success = 0;
        $errors = [];
        $data = [];

        try {
            $batch = BatchInventory::with('item')->where('id', $batch_id)->where('status', 1)->get();
            $data = $batch;
            $success = 1;
        } catch (\Exception $ex) {
            $errors = $ex->getMessage();
        }

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }

    public function request_inventory(
        $receiving_facility_id,
        $supplying_facility_id,
        $items,
        $message
    ) {
        $success = 0;
        $errors = [];
        $data = [];

        try {
            $inventory_request = new InventoryRequest();
            $inventory_request->receiving_facility_id = $receiving_facility_id;
            $inventory_request->supplying_facility_id = $supplying_facility_id;
            $inventory_request->message = $message;
            $inventory_request->created_by = $this->authenticated_user->id;
            $inventory_request->save();

            foreach ($items as $item) {
                $inventory_request_line = new InventoryRequestLine();
                $inventory_request_line->inventory_request_id = $inventory_request->id;
                $inventory_request_line->item_id = $item['id'];
                $inventory_request_line->quantity = $item['quantity'];
                $inventory_request_line->uom = 'pcs';
                $inventory_request_line->created_by = $this->authenticated_user->id;
                $inventory_request_line->save();
            }

            $success = 1;
        } catch (\Exception $ex) {
            $errors = 'An error occurred';
        }

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }

    public function view_requests($facility_id) {
        $success = 0;
        $errors = [];
        $data = [];

        try {
            $receiving_requests = InventoryRequest::where('receiving_facility_id', $facility_id)->with('items')->get();
            $supplying_request = InventoryRequest::where('supplying_facility_id', $facility_id)->with('items')->get();

            $success = 1;
            $data = $receiving_requests->merge($supplying_request);
        } catcH(\Exception $ex) {
            $errors = 'An error occurred';
        }

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }

    public function transfer_inventory($inventory_request_id) {
        $success = 0;
        $errors = [];
        $data = [];

        $inventory_request = InventoryRequest::find($inventory_request_id);

        if (! empty($inventory_request)) {
//            try {
//                $transfer_request = new InventoryTransfer();
//
//                foreach ($items as $item) {
//
//                }
//            } catch (\Exception $ex) {
//
//            }
        }

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }
}
