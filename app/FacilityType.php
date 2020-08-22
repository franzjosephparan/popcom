<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacilityType extends Model
{
    protected $table = 'facility_types';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $hidden = [];
}
