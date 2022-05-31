<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashReceipt extends Model
{
    use HasFactory;
    protected $table = 'car_exchange_category';
    
    protected $fillable = ['obf_id','cash','spcial_case','status'];
}