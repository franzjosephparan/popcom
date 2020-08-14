<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BatchInventory extends Model
{
    protected $table = 'batch_inventory';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $hidden = [];

    public function item() {
        return $this->hasOne('App\Item', 'id', 'item_id');
    }

    public function ledger() {
        return $this->hasMany('App\InventoryLedger', 'batch_inventory_id', 'id');
    }
}
