<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Services\BatchService;

class BatchInventoryController extends BaseController
{
    public $batch_service;

    public function __construct(BatchService $batch_service) {
        $this->batch_service = $batch_service;
    }

    public function add_starting_inventory(Request $request) {
        $validator = Validator::make($request->all(), [
            'batch_name' => 'required',
            'facility_id' => 'required',
            'item_id' => 'required',
            'quantity' => 'required',
            'uom' => 'required',
            'expiration_date' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
        } else {
            $response = $this->batch_service->add_starting_inventory(
                $request->input('batch_name'),
                $request->input('facility_id'),
                $request->input('item_id'),
                $request->input('quantity'),
                $request->input('uom'),
                $request->input('expiration_date')
            );
        }

        return response()->json([
            'success' => $response['success'] ?? 0,
            'errors' => $response['errors'] ?? $errors,
            'data' => $response['data'] ?? []
        ]);
    }

    public function request_inventory(Request $request) {
        $validator = Validator::make($request->all(), [
            'receiving_facility_id' => 'required',
            'supplying_facility_id' => 'required',
            'items' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
        } else {
            $response = $this->batch_service->request_inventory(
                $request->input('receiving_facility_id'),
                $request->input('supplying_facility_id'),
                $request->input('items'),
                $request->input('message')
            );
        }

        return response()->json([
            'success' => $response['success'] ?? 0,
            'errors' => $response['errors'] ?? $errors,
            'data' => $response['data'] ?? []
        ]);
    }

    public function view_requests(Request $request) {
        $validator = Validator::make($request->all(), [
            'facility_id' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
        } else {
            $response = $this->batch_service->view_requests(
                $request->input('facility_id')
            );
        }

        return response()->json([
            'success' => $response['success'] ?? 0,
            'errors' => $response['errors'] ?? $errors,
            'data' => $response['data'] ?? []
        ]);
    }
}
