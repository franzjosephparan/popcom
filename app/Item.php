<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $hidden = [];

    public function batch() {
        return $this->hasMany('App\BatchInventory', 'item_id', 'id');
    }
}
