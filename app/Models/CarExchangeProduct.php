<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarExchangeProduct extends Model
{
    use HasFactory;
    protected $table = 'car_exchange_product';
    
    protected $fillable = ['name', 'category_id' ,'status'];
}
