<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BatchInventory extends Model
{
    protected $table = 'batch_inventory';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $hidden = [];
}
