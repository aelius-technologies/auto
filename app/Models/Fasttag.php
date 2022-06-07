<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Fasttag extends Model{
    use HasFactory;
    protected $table = 'fasttags';
    
    protected $fillable = [
        'tag_id',
        'amount',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];

    public function export($slug){
        $collection = DB::table('fasttags')
                        ->select('tag_id', 'amount', 'status');

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
