<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        if($this->method() == 'PATCH'){
            return [
                'name' => 'required|max:255|unique:permissions,name,'.$this->id,
                'guard_name' => 'required|regex:/^[\pL\s\-]+$/u|max:255'
            ];
        }else{
            return [
                'name' => 'required|max:255|unique:permissions,name',
                'guard_name' => 'required|regex:/^[\pL\s\-]+$/u|max:255'
            ];
        }
    }

    public function messages(){
        return [
            'name.required' => 'Please enter name',
            'name.max' => 'Please enter name maximum 255 characters',
            'name.unique' => 'Please enter unique name',
            'guard_name.required' => 'Please enter guard name',
            'nameguard_name.regex' => 'Please enter correct guard name',
            'guard_name.max' => 'Please enter name maximum 255 characters'
        ];
    }
}
