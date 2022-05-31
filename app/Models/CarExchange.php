<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarExchange extends Model
{
    use HasFactory;
    protected $table = 'car_exchange';
    
    protected $fillable = ['category_id', 'product_id' ,'engine_number' ,'chassis_number' ,'price','status'];
}
