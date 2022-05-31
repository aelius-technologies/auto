<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model{
    use HasFactory;
    protected $table = 'orders';
    
    protected $fillable = [
        'order_id',
        'branch_id',
        'obf_id',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];
}
