<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTemplate extends Model
{
    protected $table = 'product_template';

    protected $fillable = [
    
    'brand_id',
    'model_id',
    'category_id',
    'subcategory_id',
    'created_at',
    ];
}
