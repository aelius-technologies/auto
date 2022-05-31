<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialRegistrationNumber extends Model{
    use HasFactory;
    protected $table = 'special_registration_number';
    
    protected $fillable = [
        'number',
        'amount',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];
}
