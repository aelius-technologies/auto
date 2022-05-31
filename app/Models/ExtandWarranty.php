<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtandWarranty extends Model
{
    use HasFactory;
    protected $table = 'extand_warranties';
    
    protected $fillable = ['years','amount' ,'status'];
}
