<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFacility extends Model
{
    protected $table = 'user_facilities';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $hidden = [];

    public function facilities() {
        return $this->hasMany('App\Facility', 'id', 'facility_id');
    }

    public function user() {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
