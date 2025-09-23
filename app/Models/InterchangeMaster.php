<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterchangeMaster extends Model
{
    protected $table = 'interchnage_master';

    protected $fillable = [
        'product1',
        'year_id1',
        'product2',
        'year_id2','created_at'
    ];
}
