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
        $item_id,
        $quantity,
        $uom,
        $expiration_date
    ) {
        $success = 0;
        $errors = [];
        $data = [];

        DB::beginTransaction();
        try {
            $batch = new BatchInventory();
            $batch->batch_name = $batch_name;
            $batch->facility_id = $facility_id;
            $batch->item_id = $item_id;
            $batch->quantity = $quantity;
            $batch->uom = $uom;
            $batch->expiration_date = Carbon::createFromTimestamp($expiration_date)->toDateTimeString();
            $batch->status = 1;
            $batch->created_by = $this->authenticated_user->id;
            $batch->save();

            $ledger = new InventoryLedger();
            $ledger->batch_inventory_id = $batch['id'];
            $ledger->item_id = $item_id;
            $ledger->facility_id = $facility_id;
            $ledger->quantity = $quantity;
            $ledger->uom = $uom;
            $ledger->transaction_type = 'starting';
            $ledger->created_by = $this->authenticated_user->id;
            $ledger->save();

            $success = 1;
            $data = $batch;
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
        $message,
        $expected_delivery_date
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
            $inventory_request->expected_delivery_date = Carbon::createFromTimestamp($expected_delivery_date)->toDateTimeString();;
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
            $errors = $ex->getMessage();
            DB::rollBack();
        }

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }

    public function edit_request(
        $request_inventory_id,
        $receiving_facility_id,
        $supplying_facility_id,
        $items,
        $message,
        $expected_delivery_date
    ) {
        $success = 0;
        $errors = [];
        $data = [];

        DB::beginTransaction();
        try {
            $inventory_request = InventoryRequest::find($request_inventory_id);
            $inventory_request->receiving_facility_id = $receiving_facility_id;
            $inventory_request->supplying_facility_id = $supplying_facility_id;
            $inventory_request->message = $message;
            $inventory_request->expected_delivery_date = Carbon::createFromTimestamp($expected_delivery_date)->toDateTimeString();;
            $inventory_request->updated_by = $this->authenticated_user->id;
            $inventory_request->save();
            $final_data = $inventory_request;
            $temp_items = [];

            foreach ($items as $item) {
                $inventory_request_line = InventoryRequestLine::where('inventory_request_id', $request_inventory_id)->where('item_id', $item['item_id'])->get()->toArray();

                if (! empty($inventory_request_line))
                    $inventory_request_line = $inventory_request_line[0];

                if (empty($inventory_request_line)) {
                    $inventory_request_line = new InventoryRequestLine();
                    $inventory_request_line->inventory_request_id = $inventory_request->id;
                    $inventory_request_line->item_id = $item['item_id'];
                    $inventory_request_line->quantity = $item['quantity'];
                    $inventory_request_line->uom = $item['uom'];
                    $inventory_request_line->created_by = $this->authenticated_user->id;
                    $inventory_request_line->save();
                    $final_data['items'][] = $inventory_request_line;
                } else {
                    $inventory_request_line = InventoryRequestLine::find($inventory_request_line['id']);
                    $inventory_request_line->item_id = $item['item_id'];
                    $inventory_request_line->quantity = $item['quantity'];
                    $inventory_request_line->uom = $item['uom'];
                    $inventory_request_line->updated_by = $this->authenticated_user->id;
                    $inventory_request_line->save();
                    $final_data['items'][] = $inventory_request_line;
                }

                array_push($temp_items, $item['item_id']);
            }

            foreach ($inventory_request->items as $item) {
                if (! in_array($item->item_id, $temp_items)) {
                    $inventory_request_line = InventoryRequestLine::find($item->id)->forceDelete();
                }
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

    public function cancel_request($request_inventory_id) {
        $success = 0;
        $errors = [];
        $data = [];

        DB::beginTransaction();
        try {
            $inventory_request = InventoryRequest::find($request_inventory_id);
            $inventory_request->active = 0;
            $inventory_request->updated_by = $this->authenticated_user->id;
            $inventory_request->save();

            $data = $inventory_request;
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

    public function decline_request($request_inventory_id) {
        $success = 0;
        $errors = [];
        $data = [];

        DB::beginTransaction();
        try {
            $inventory_request = InventoryRequest::find($request_inventory_id);
            $inventory_request->status = 'declined';
            $inventory_request->status_by = $this->authenticated_user->id;
            $inventory_request->status_at = Carbon::now()->toDateTimeString();
            $inventory_request->save();

            $data = $inventory_request;
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

    public function view_requests($facility_id) {
        $success = 0;
        $errors = [];
        $data = [];

        try {
            $receiving_requests = InventoryRequest::where('receiving_facility_id', $facility_id)->with('transfer.lines', 'items.item')->get();
            $supplying_requests = InventoryRequest::where('supplying_facility_id', $facility_id)->with('transfer.lines', 'items.item')->get();

            $success = 1;
            $data = $receiving_requests->merge($supplying_requests);
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

            $request->status = 'accepted';
            $request->status_by = $this->authenticated_user->id;
            $request->status_at = Carbon::now()->toDateTimeString();
            $request->inventory_transfer_id = $transfer->id;
            $request->save();

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

    public function update_transfer_status($inventory_transfer_id) {
        $success = 0;
        $errors = [];
        $data = [];

        DB::beginTransaction();
        try {
            $transfer = InventoryTransfer::find($inventory_transfer_id);
            $transfer->status = 'in transit';
            $transfer->updated_by = $this->authenticated_user->id;
            $transfer->save();

            $data = $transfer;
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

    public function receive_inventory($inventory_transfer_id) {
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
                $batch->facility_id = $transfer->receiving_facility_id;
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

            $request = InventoryRequest::where('inventory_transfer_id', $inventory_transfer_id)->get()->toArray();

            if (! empty($request))
                $request = $request[0];

            $request = InventoryRequest::find($request['id']);
            $request->active = 0;
            $request->updated_by = $this->authenticated_user->id;
            $request->save();

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

    public function get_to_receive_inventory($facility_id) {
        $success = 0;
        $errors = [];
        $data = [];

        DB::beginTransaction();
        try {
            $request = InventoryTransfer::where('receiving_facility_id', $facility_id)->where('status', 'in transit')->with('request.items.item', 'lines')->get()->toArray();

            $data = $request;
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
