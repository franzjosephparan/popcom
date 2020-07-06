<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\BatchInventory;
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
        $item_id,
        $quantity,
        $uom,
        $expiration_date
    ) {
        $success = 0;
        $errors = [];
        $data = [];

        try {
            $batch = new BatchInventory();
            $batch->batch_name = $batch_name;
            $batch->facility_id = $facility_id;
            $batch->item_id = $item_id;
            $batch->quantity = $quantity;
            $batch->uom = $uom;
            $batch->expiration_date = $expiration_date;
            $batch->status = 1;
            $batch->created_by = $this->authenticated_user->id;
            $batch->save();

            $success = 1;
            $data = $batch->toArray();
        } catch (\Exception $ex) {
            $errors = 'An error occurred';
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

        DB::transaction(function() {

        });

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }
}
