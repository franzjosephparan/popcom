<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $table = 'facilities';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function users() {
        return $this->hasMany('App\User', 'facility_id', 'id');
    }

    public function type() {
        return $this->hasOne('App\FacilityType', 'id', 'facility_type');
    }
}
