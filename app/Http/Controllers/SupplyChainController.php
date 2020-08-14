<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Services\BatchService;

class SupplyChainController extends BaseController
{
    public $batch_service;

    public function __construct(BatchService $batch_service) {
        $this->batch_service = $batch_service;
    }
}
