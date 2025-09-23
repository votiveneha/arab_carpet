<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniqueProduct extends Model
{
    protected $table = 'unique_product';

    protected $fillable = [
    
    'brand_id',
    'model_id',
    'category_id',
    'subcategory_id',
    'created_at',
    ];
}
