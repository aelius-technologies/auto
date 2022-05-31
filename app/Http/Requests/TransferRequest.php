<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        if($this->method() == 'PATCH'){
            $return = [
                'to_branch' => 'required',
                'product_id' => 'required'
            ];

            if(auth()->user()->roles->pluck('name')[0] == 'admin')
                $return['from_branch'] = 'required';

            return $return;
        }else{
            $return = [
                'to_branch' => 'required',
                'product_id' => 'required'
            ];

            if(auth()->user()->roles->pluck('name')[0] == 'admin')
                $return['from_branch'] = 'required';

            return $return;
        }
    }

    public function messages(){
        return [
            'to_branch.required' => 'Please select to branch',
            'product_id.required' => 'Please select product',
            'from_branch.required' => 'Please select from branch',
        ];
    }
}
