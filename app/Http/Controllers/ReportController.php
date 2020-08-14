<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Services\ReportService;

class ReportController extends BaseController
{
    public $report_service;

    public function __construct(ReportService $report_service) {
        $this->report_service = $report_service;
    }

    public function generate_report(Request $request) {
        $validator = Validator::make($request->all(), [
            'facility_id' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
        } else {
            $file_name = $this->report_service->generate_report(
                $request->input('facility_id')
            );
        }

        return response()->download(public_path($file_name));
    }
}
