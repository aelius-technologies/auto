<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branches';
    
    protected $fillable = ['name','city', 'address', 'email', 'contact_number','manager' ,'manager_contact_number' , 'gst' ,'status'];
}
