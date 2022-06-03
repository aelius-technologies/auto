<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventoryAcceptRequest extends FormRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        $return = [
            'obf_id' => 'required'
        ];

        return $return;
    }

    public function messages(){
        return [
            'obf_id.required' => 'Please select customer'
        ];
    }
}
