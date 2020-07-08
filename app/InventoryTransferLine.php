<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryTransferLine extends Model
{
    protected $table = 'inventory_transfer_lines';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $hidden = [];
}
