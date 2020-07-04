<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $table = 'facilities';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'facility_name',
        'address',
        'region',
        'province',
        'longitude',
        'latitude',
        'facility_type',
        'status',
        'created_by',
        'updated_by'
    ];

    public function user() {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
