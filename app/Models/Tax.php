<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model{
    use HasFactory;
    protected $table = 'taxes';
    
    protected $fillable = [
        'name',
        'percentage',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];
}
