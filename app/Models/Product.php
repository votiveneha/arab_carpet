<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';

    public $timestamps = false;

    protected $fillable = [
    'seller_id',
    'brand_id',
    'model_id',
    'category_id',
    'subcategory_id',
    'product_milage',
    'product_description',
    'product_price',
    'product_note',
    'quantity',
    'product_type',
    'created_at',
    'admin_product_id',
    'generation_id',
    'part_type_id',
    'is_active',
    'is_deleted'
];
}
