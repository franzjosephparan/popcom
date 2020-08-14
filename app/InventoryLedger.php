<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryLedger extends Model
{
    protected $table = 'inventory_ledgers';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $hidden = [];

    public function facility() {
        return $this->hasOne('App\Facility', 'id', 'facility_id');
    }

    public function batch() {
        return $this->hasOne('App\BatchInventory', 'id', 'batch_inventory_id');
    }

    public function transfer() {
        return $this->hasOne('App\InventoryTransfer', 'id', 'inventory_transfer_id');
    }
}
