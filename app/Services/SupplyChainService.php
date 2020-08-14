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

class SupplyChainService {
    private $authenticated_user;

    public function __construct() {
        $this->authenticated_user = Auth::user();
    }
}
