<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    protected $table = 'inventory';
    
    protected $fillable = ['category_id','name' , 'veriant' , 'key_number', 'engine_number', 'chassis_number','vin_number' ,'ex_showroom_price' , 'interior_color', 'exterior_color' ,'status'];
}
