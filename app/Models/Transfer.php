<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model{
    use HasFactory;
    protected $table = 'transfer';
    
    protected $fillable = [
        'from_branch',
        'to_branch',
        'product_id',
        'inventory_id',
        'transfer_fee',
        'reason',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];
}
