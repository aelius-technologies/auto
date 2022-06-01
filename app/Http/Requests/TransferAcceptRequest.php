<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferAcceptRequest extends FormRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        $return = [
            'product' => 'required',
            'transfer_fee' => 'required'
        ];

        return $return;
    }

    public function messages(){
        return [
            'product.required' => 'Please select product',
            'transfer_fee.required' => 'Please select to branch'
        ];
    }
}
