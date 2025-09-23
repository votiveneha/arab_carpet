<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
     protected $table = 'product_img'; // ✅ Update this if your table name is different
    
    public $timestamps = true;

    protected $fillable = [
    'product_id',
    'product_image',
    
];
}
