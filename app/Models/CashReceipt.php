<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashReceipt extends Model{
    use HasFactory;
    protected $table = 'cash_receipt';

    protected $fillable = [
        'obf_id',
        'amount',
        'spcial_case',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];
}