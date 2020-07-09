<?php
namespace App\Services;

use App\InventoryLedger;
use App\InventoryTransfer;
use App\InventoryTransferLine;
use Illuminate\Support\Facades\Auth;
use App\BatchInventory;
use App\Item;
use App\InventoryRequest;
use App\InventoryRequestLine;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        DB::beginTransaction();
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

                $ledger = new InventoryLedger();
                $ledger->batch_inventory_id = $batch['id'];
                $ledger->item_id = $item['item_id'];
                $ledger->facility_id = $facility_id;
                $ledger->quantity = $item['quantity'];
                $ledger->uom = $item['uom'];
                $ledger->transaction_type = 'starting';
                $ledger->created_by = $this->authenticated_user->id;
                $ledger->save();
            }

            $success = 1;
            $data = $batch_data;
            DB::commit();
        } catch (\Exception $ex) {
            $errors = $ex->getMessage();
            DB::rollBack();
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

        DB::beginTransaction();
        try {
            $batch = BatchInventory::find($batch_id);
            $previous_quant = $batch->quantity;
            $batch->quantity = $quantity;
            $batch->updated_by = $this->authenticated_user->id;
            $batch->save();

            $ledger = new InventoryLedger();
            $ledger->batch_inventory_id = $batch->id;
            $ledger->item_id = $batch->item_id;
            $ledger->facility_id = $batch->facility_id;
            $ledger->quantity = (int)$quantity - (int)$previous_quant;
            $ledger->uom = $batch->uom;
            $ledger->transaction_type = 'adjustment';
            $ledger->created_by = $this->authenticated_user->id;
            $ledger->save();

            $data = $batch;
            $success = 1;
            DB::commit();
        } catch (\Exception $ex) {
            $errors = 'An error occurred';
            DB::rollBack();
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
            $inventory = [];
            $items = Item::where('status', 1)->get();

            foreach ($items as $key => $item) {
                $batch = BatchInventory::where('item_id', $item['id'])
                    ->where('facility_id', $facility_id)
                    ->where('status', 1)->get();

                $inventory[] = [
                    'item' => $item,
                    'batch' => $batch
                ];
            }

            $data = $inventory;
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

    public function get_facility_batches($facility_id) {
        $success = 0;
        $errors = [];
        $data = [];

        try {
            $batches = BatchInventory::with('item')->where('facility_id', $facility_id)->where('status', 1)->get();
            $data = $batches;
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

    public function get_item_batches($item_id) {
        $success = 0;
        $errors = [];
        $data = [];

        try {
            $batches = BatchInventory::with('item')->where('item_id', $item_id)->where('status', 1)->get();
            $data = $batches;
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

        DB::beginTransaction();
        try {
            $inventory_request = new InventoryRequest();
            $inventory_request->receiving_facility_id = $receiving_facility_id;
            $inventory_request->supplying_facility_id = $supplying_facility_id;
            $inventory_request->message = $message;
            $inventory_request->created_by = $this->authenticated_user->id;
            $inventory_request->save();
            $final_data = $inventory_request;

            foreach ($items as $item) {
                $inventory_request_line = new InventoryRequestLine();
                $inventory_request_line->inventory_request_id = $inventory_request->id;
                $inventory_request_line->item_id = $item['item_id'];
                $inventory_request_line->quantity = $item['quantity'];
                $inventory_request_line->uom = $item['uom'];
                $inventory_request_line->created_by = $this->authenticated_user->id;
                $inventory_request_line->save();
                $final_data['items'][] = $inventory_request_line;
            }

            $data = $final_data;
            $success = 1;
            DB::commit();
        } catch (\Exception $ex) {
            $errors = 'An error occurred';
            DB::rollBack();
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

    public function transfer_inventory(
        $request_inventory_id,
        $items,
        $message
    ) {
        $success = 0;
        $errors = [];
        $data = [];

        DB::beginTransaction();
        try {
            $request = InventoryRequest::find($request_inventory_id);
            $request->status = 'accepted';
            $request->save();

            $transfer = new InventoryTransfer();
            $transfer->receiving_facility_id = $request->receiving_facility_id;
            $transfer->supplying_facility_id = $request->supplying_facility_id;
            $transfer->message = $message;
            $transfer->status = 'prepared';
            $transfer->created_by = $this->authenticated_user->id;
            $transfer->save();
            $final_data = $transfer->toArray();

            foreach ($items as $item) {
                $transfer_line = new InventoryTransferLine();
                $transfer_line->inventory_transfer_id = $transfer->id;
                $transfer_line->item_id = $item['item_id'];
                $transfer_line->batch_inventory_id = $item['batch_inventory_id'];
                $transfer_line->quantity = $item['quantity'];
                $transfer_line->uom = $item['uom'];
                $transfer_line->created_by = $this->authenticated_user->id;
                $transfer_line->save();

                $batch = BatchInventory::find($item['batch_inventory_id']);
                $batch->quantity = (int)$batch->quantity - (int)$item['quantity'];
                $batch->updated_by = $this->authenticated_user->id;
                $batch->save();

                $ledger = new InventoryLedger();
                $ledger->batch_inventory_id = $batch->id;
                $ledger->item_id = $batch->item_id;
                $ledger->facility_id = $batch->facility_id;
                $ledger->quantity = (int)$item['quantity'] * -1;
                $ledger->uom = $batch->uom;
                $ledger->transaction_type = 'transfer';
                $ledger->created_by = $this->authenticated_user->id;
                $ledger->save();

                $final_data['items'][] = $transfer_line;
            }

            $data = $final_data;
            $success = 1;
            DB::commit();
        } catch (\Exception $ex) {
            $errors = $ex->getMessage();
            DB::rollBack();
        }

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }

    public function receive_inventory($inventory_transfer_id, $receiving_inventory_id) {
        $success = 0;
        $errors = [];
        $data = [];

        DB::beginTransaction();
        try {
            $transfer = InventoryTransfer::find($inventory_transfer_id);
            $transfer->status = 'received';
            $transfer->accepted_by = $this->authenticated_user->id;
            $transfer->accepted_at = Carbon::now()->toDateTimeString();
            $transfer->save();

            foreach ($transfer->lines as $line) {
                $line_batch = BatchInventory::find($line->batch_inventory_id);
                $batch = new BatchInventory();
                $batch->batch_name = $line_batch->batch_name;
                $batch->facility_id = $receiving_inventory_id;
                $batch->item_id = $line->item_id;
                $batch->quantity = $line->quantity;
                $batch->uom = $line->uom;
                $batch->expiration_date = $line_batch->expiration_date;
                $batch->status = 1;
                $batch->created_by = $this->authenticated_user->id;
                $batch->save();

                $ledger = new InventoryLedger();
                $ledger->batch_inventory_id = $batch->id;
                $ledger->item_id = $batch->item_id;
                $ledger->facility_id = $batch->facility_id;
                $ledger->quantity = $batch->quantity;
                $ledger->uom = $batch->uom;
                $ledger->transaction_type = 'receive';
                $ledger->created_by = $this->authenticated_user->id;
                $ledger->save();
            }

            $success = 1;
            DB::commit();
        } catch (\Exception $ex) {
            $errors = $ex->getMessage();
            DB::rollBack();
        }

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }
}
