<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryTransferLines extends Model
{
    protected $table = 'inventory_transfer_lines';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $hidden = [];
}
