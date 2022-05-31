<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        if($this->method() == 'POST'){
            return [
                'name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
                'email' => 'required|email|unique:users,email,'.$this->id,
                'phone' => 'required|digits:10|unique:users,phone,'.$this->id
            ];
        }
    }

    public function messages(){
        return [
            'name.required' => 'Please enter name',
            'name.regex' => 'Please enter correct name',
            'name.max' => 'Please enter name maximum 255 characters',
            'email.required' => 'Please enter email',
            'email.email' => 'Please enter valid email',
            'email.unique' => 'Please enter unique email',
            'phone.required' => 'Please enter phone number',
            'phone.digits' => 'Please enter 10 digit number',
            'phone.unique' => 'Please enter unique phone'
        ];
    }
}
