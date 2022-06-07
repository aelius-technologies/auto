<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class ExtandWarranty extends Model{
    use HasFactory;
    protected $table = 'extand_warranties';
    
    protected $fillable = [
        'years',
        'amount',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];

    public function export($slug){
        $collection = DB::table('extand_warranties')
                        ->select('years', 'amount', 'status');

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
