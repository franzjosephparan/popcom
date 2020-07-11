<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryTransfer extends Model
{
    protected $table = 'inventory_transfers';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $hidden = [];

    public function lines() {
        return $this->hasMany('App\InventoryTransferLine', 'inventory_transfer_id', 'id');
    }

    public function request() {
        return $this->hasMany('App\InventoryRequest', 'inventory_transfer_id', 'id');
    }
}
