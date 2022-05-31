<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model{
    use HasFactory;
    protected $table = 'products';
    
    protected $fillable = [
        'category_id', 
        'name', 
        'veriant', 
        'key_number', 
        'engine_number', 
        'chassis_number', 
        'vin_number', 
        'ex_showroom_price', 
        'interior_color', 
        'exterior_color',
        'is_applicable_for_mcp',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];
}
