<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryTransfer extends Model
{
    protected $table = 'inventory_transfer';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $hidden = [];
}
