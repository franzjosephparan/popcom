<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryRequestLine extends Model
{
    protected $table = 'inventory_request_lines';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $hidden = [];

    public function item() {
        return $this->hasOne('App\Item', 'id', 'item_id');
    }
}
