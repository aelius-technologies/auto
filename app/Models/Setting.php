<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model{
    use HasFactory;
    protected $table = 'settings';

    protected $fillable = ['key', 'value', 'type', 'created_by', 'created_at', 'updated_by', 'updated_at'];
}
