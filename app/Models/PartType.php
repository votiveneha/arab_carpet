<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartType extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'part_type'; // ✅ Update this if your table name is different

    protected $fillable = ['product_temp_id', 'part_type_label','created_at'];
    public $timestamps = true;
}
