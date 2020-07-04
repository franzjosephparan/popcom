<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryLedger extends Model
{
    protected $table = 'inventory_ledgers';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $hidden = [];
}
