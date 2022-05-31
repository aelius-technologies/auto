<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accessory extends Model{
    use HasFactory;
    protected $table = 'accessories';
    
    protected $fillable = [
        'name',
        'type',
        'price',
        'hsn_number',
        'model_number',
        'model_type',
        'warranty',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];
}
