<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductYear extends Model
{
     protected $table = 'product_year'; // ✅ Update this if your table name is different

   
    protected $fillable = [
            'product_id',
            'make_year_id',
        ];
}
