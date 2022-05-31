<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
    use HasFactory;
    protected $table = 'allocation';
    
    protected $fillable = ['customer_name', 'billing_date','obf_id','product_id','dsa_or_broker','disb_amount','payment_due','fatd','iatd','tentetive_delivery_date','reason','status'];
}
