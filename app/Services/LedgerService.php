<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\InventoryLedger;

class LedgerService {
    private $authenticated_user;

    public function __construct() {
        $this->authenticated_user = Auth::user();
    }

    public function get_ledger($type, $id, $limit = 5, $page = 1) {
        $offset = ($page * $limit) - $limit;

        if ($type == 'facility') {
            $ledger = InventoryLedger::where('facility_id', $id)
                ->offset($offset)
                ->limit($limit)
                ->orderby('created_at', 'desc')
                ->with('user')
                ->with('item')
                ->get()
                ->toArray();
        } else {
            $ledger = InventoryLedger::where('batch_inventory_id', $id)
                ->offset($offset)
                ->limit($limit)
                ->orderby('created_at', 'desc')
                ->width('user')
                ->with('item')
                ->get()
                ->toArray();
        }

        return $ledger ?? [];
    }

    public function get_facility_ledger($facility_id, $limit, $page) {
        $success = 0;
        $errors = [];
        $data = [];

        try {
            $ledger = $this->get_ledger('facility', $facility_id, $limit, $page);
            $success = 1;
            $data = $ledger;
        } catch (\Exception $e) {
            $errors = $e->getMessage();
        }

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }

    public function get_batch_ledger($batch_inventory_id, $limit, $page) {
        $success = 0;
        $errors = [];
        $data = [];

        try {
            $ledger = $this->get_ledger('batch', $batch_inventory_id, $limit, $page);
            $success = 1;
            $data = $ledger;
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
