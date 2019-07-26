<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeFeature extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'se_id', 'status', 'translate','created_by','deleted_by','updated_by'
    ];
}
