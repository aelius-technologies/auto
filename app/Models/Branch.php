<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Branch extends Model{
    use HasFactory;
    protected $table = 'branches';
    
    protected $fillable = [
        'name',
        'city',
        'address',
        'email',
        'contact_number',
        'manager',
        'manager_contact_number',
        'gst',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];

    public function export($slug){
        $collection = DB::table('branches')
                        ->select('name', 'city', 'address', 'email', 'contact_number', 'manager', 'manager_contact_number', 'gst', 'status');

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
