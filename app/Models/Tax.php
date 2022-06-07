<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

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

    public function export($slug){
        $collection = DB::table('taxes')
                        ->select('name','percentage' ,'status');

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
