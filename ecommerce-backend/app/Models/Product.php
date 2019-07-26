<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'category_id','se_id','tags','description','product_images',
        'product_videos','price','is_service','status','created_by',
        'updated_by','deleted_by','translate'
    ];

    public function hot()
    {
        return $this->hasOne('App\Models\Hot', 'product_id');
    }
    public function feature()
    {
        return $this->hasOne('App\Models\Feature', 'product_id');
    }
}
