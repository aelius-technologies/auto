<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ImportProduct implements ToModel ,WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function startRow(): int
    {
        return 6;
    }
    public function model(array $row)
    {
        
        $category = (object)'';
        if(isset($row[0]) && !empty($row[1])){
            $category = Category::select('id')->where(['name' => $row[0]])->first();
            if(!$category){
                session(['error' => 'No Product Category Found In at least one record!']);
                return null;
            }
        }else{
            $category->id = null;
        }
        $data = [
            'category_id' => $category->id,
            'name' => $row[1],
            'veriant' =>"$row[2]",
            'ex_showroom_price' => $row[3],
            'interior_color' => $row[4],
            'exterior_color' => $row[5],
            'is_applicable_for_mcp' => $row[6],
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => auth()->user()->id,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => auth()->user()->id
        ];
        $products = Product::create($data);
        return $products;
    }
}
