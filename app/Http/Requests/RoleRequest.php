<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        if($this->method() == 'PATCH'){
            return [
                'name' => 'required|regex:/^[\pL\s\-]+$/u|max:255|unique:roles,name,'.$this->id,
                'permissions' => 'required|array|min:1'
            ];
        }else{
            return [
                'name' => 'required|regex:/^[\pL\s\-]+$/u|max:255|unique:roles,name',
                'permissions' => 'required|array|min:1'
            ];
        }
    }

    public function messages(){
        return [
            'name.required' => 'Please Enter Name',
            'name.regex' => 'Please enter correct name',
            'name.unique' => 'Please enter unique name',
            'name.max' => 'Please enter name maximum 255 characters',
            'permissions.required' => 'Please select one permission atleast',
            'permissions.array' => 'Please select one permission atleast',
            'permissions.min' => 'Please select one permission atleast'
        ];
    }
}
