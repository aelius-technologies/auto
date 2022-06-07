<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use DB;

class User extends Authenticatable{
    use HasFactory, Notifiable, HasApiTokens , HasRoles;

    public function guardName(){
        return "web";
    }

    protected $fillable = [
        'first_name',
        'last_name',
        'branch',
        'contact_number',
        'email',
        'password',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['email_verified_at' => 'datetime'];

    public function export($slug){
        $collection = DB::table('users')
                        ->select('first_name', 'last_name', 'email', 'status'); 
        
        if($slug != 'all')
            $collection->where(['status' => $slug]);
        
        $data = $collection->get();

        if($data->isNotEmpty()){
            return $data;
        }else{
            return null;
        }
    }
}
