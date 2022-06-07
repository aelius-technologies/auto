<?php

namespace App\Models;

use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Product extends Model{
    use HasFactory;
    protected $table = 'products';
    
    protected $fillable = [
        'category_id', 
        'name', 
        'veriant', 
        'key_number', 
        'engine_number', 
        'chassis_number', 
        'vin_number', 
        'ex_showroom_price', 
        'interior_color', 
        'exterior_color',
        'is_applicable_for_mcp',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];


    public function export($slug){
        $collection = DB::table('products')
                        ->select('products.name','products.veriant','products.ex_showroom_price','products.interior_color','products.exterior_color','products.is_applicable_for_mcp','products.status', 'categories.name AS category_name')
                        ->leftjoin('categories' ,'products.category_id' ,'categories.id');

        if($slug != 'all')
            $collection->where(['products.status' => $slug]);
        
        $data = $collection->get();
        
        if($data->isNotEmpty()){
            return $data;
        }else{
            return null;
        }
    }
}
