<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Department extends Model{
    use HasFactory;
    protected $table = 'department';
    
    protected $fillable = [
        'name',
        'branch_id',
        'email',
        'number',
        'authorised_person',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];

    public function export($slug){
        $collection = DB::table('department as d')
                        ->select('d.name', 'b.name as branch_name', 'd.email', 'd.number', 'd.authorised_person', 'd.status')
                        ->leftjoin('branches as b', 'b.id', 'd.branch_id');

        if($slug != 'all')
            $collection->where(['d.status' => $slug]);
        
        $data = $collection->get();
        
        if($data->isNotEmpty()){
            return $data;
        }else{
            return null;
        }
    }
}
