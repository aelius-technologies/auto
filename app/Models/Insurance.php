<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insurance extends Model{
    use HasFactory;
    protected $table = 'insurance';
    
    protected $fillable = [
        'name',
        'type',
        'years',
        'amount',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];
}
