<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerTag extends Model
{
    protected $table = 'seller_tags';

    protected $fillable = [
    'seller_id',
    'parent_id',
    'model_id',
    'make_id',
    'part_id',
    'part_type_id',
    'shop_id'
];


}
