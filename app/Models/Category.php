<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model{
    use HasFactory;
    protected $table = 'categories';
    
    protected $fillable = [
        'name',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];
}
