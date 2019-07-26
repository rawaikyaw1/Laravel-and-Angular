<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sector extends Model
{
	 use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'status', 'translate','created_by','deleted_by','updated_by'
    ];
}
