<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialEnterprise extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'email', 'password','phone',
        'address','se_images','se_videos','description',
        'sector_id','sgd_id','translate',
        'deleted_by','updated_by','created_by'
    ];
}
