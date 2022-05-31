<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccessRequest extends FormRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        if($this->method() == 'PATCH'){
            return [
                'role' => 'required',
                'permissions' => 'required|array|min:1'
            ];
        }
    }

    public function messages(){
        return [
            'role.required' => 'Please select role',
            'permissions.required' => 'Please select one permission atleast'
        ];
    }
}
