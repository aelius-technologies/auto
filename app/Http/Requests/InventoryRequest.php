<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventoryRequest extends FormRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        if($this->method() == 'PATCH'){
            return [
                'category_id' => 'required',
                'name' => 'required|max:255',
                'branch_id' => 'required',
                'veriant' => 'required|max:255',
                'key_number' => 'required|max:255',
                'engine_number' => 'required|max:255',
                'chassis_number' => 'required|max:255',
                'vin_number' => 'required|max:255',
                'ex_showroom_price' => 'required|max:255',
                'interior_color' => 'required|max:255',
                'exterior_color' => 'required|max:255'
            ];
        }else{
            return [
                'category_id' => 'required',
                'name' => 'required|max:255',
                'branch_id' => 'required',
                'veriant' => 'required|max:255',
                'key_number' => 'required|max:255',
                'engine_number' => 'required|max:255',
                'chassis_number' => 'required|max:255',
                'vin_number' => 'required|max:255',
                'ex_showroom_price' => 'required|max:255',
                'interior_color' => 'required|max:255',
                'exterior_color' => 'required|max:255'
            ];
        }
    }

    public function messages(){
        return [
            'category_id' => 'Please select category',
            'name' => 'Please enter name',
            'branch_id' => 'Please select branch',
            'veriant' => 'Please enter veriant',
            'key_number' => 'Please enter key number',
            'engine_number' => 'Please enter engine number',
            'chassis_number' => 'Please enter chassis number',
            'vin_number' => 'Please enter vin number',
            'ex_showroom_price' => 'Please enter ex showroom price',
            'interior_color' => 'Please enter interior color',
            'exterior_color' => 'Please enter exterior color'
        ];
    }
}
