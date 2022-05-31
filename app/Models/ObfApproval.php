<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObfApproval extends Model{
    use HasFactory;
    protected $table = 'obf_approval';
    
    protected $fillable = [
        'obf_id',
        'reason',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];
}
