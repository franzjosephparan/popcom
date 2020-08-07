<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Services\LedgerService;

class LedgerController extends BaseController
{
    public $ledger_service;

    public function __construct(LedgerService $ledger_service) {
        $this->ledger_service = $ledger_service;
    }

    public function get_facility_ledger(Request $request) {
        $validator = Validator::make($request->all(), [
            'facility_id' => 'required',
            'limit' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
        } else {
            $response = $this->ledger_service->get_facility_ledger(
                $request->input('facility_id'),
                $request->input('limit'),
                $request->input('page')
            );
        }

        return response()->json([
            'success' => $response['success'] ?? 0,
            'errors' => $response['errors'] ?? $errors,
            'data' => $response['data'] ?? []
        ]);
    }

    public function get_batch_ledger(Request $request) {
        $validator = Validator::make($request->all(), [
            'batch_inventory_id' => 'required',
            'limit' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
        } else {
            $response = $this->ledger_service->get_batch_ledger(
                $request->input('batch_inventory_id'),
                $request->input('limit'),
                $request->input('page')
            );
        }

        return response()->json([
            'success' => $response['success'] ?? 0,
            'errors' => $response['errors'] ?? $errors,
            'data' => $response['data'] ?? []
        ]);
    }
}
