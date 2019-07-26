<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsPost extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'title','content','thumbnail','se_id','news_category_id',
         'status','translate','created_by','deleted_by','updated_by'
    ];
}
