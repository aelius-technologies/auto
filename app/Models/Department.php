<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model{
    use HasFactory;
    protected $table = 'department';
    
    protected $fillable = [
        'name',
        'branch_id',
        'email',
        'number',
        'authorised_person',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];
}
