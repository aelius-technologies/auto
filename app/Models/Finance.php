<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Finance extends Model{
    use HasFactory;
    protected $table = 'finance';
    
    protected $fillable = [
        'name',
        'branch_id',
        'dsa_or_broker',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];

    public function export($slug){
        $collection = DB::table('finance as f')
                        ->select('f.name', 'b.name as branch_name', 'f.dsa_or_broker', 'f.status')
                        ->leftjoin('branches as b', 'b.id', 'f.branch_id');

        if($slug != 'all')
            $collection->where(['f.status' => $slug]);
        
        $data = $collection->get();
        
        if($data->isNotEmpty()){
            return $data;
        }else{
            return null;
        }
    }
}
