<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasttag extends Model
{
    use HasFactory;
    protected $table = 'fasttags';
    
    protected $fillable = ['tag_id' , 'amount' ,'status'];
}
