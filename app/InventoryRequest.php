<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryRequest extends Model
{
    protected $table = 'inventory_requests';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $hidden = [];

    public function items() {
        return $this->hasMany('App\InventoryRequestLine', 'inventory_request_id');
    }

    public function transfer() {
        return $this->hasOne('App\InventoryTransfer', 'id', 'inventory_transfer_id');
    }
}
