<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sgd extends Model
{
	use SoftDeletes;
    protected $fillable = [
        'name', 'status', 'translate','created_by','deleted_by','updated_by'
    ];
}
