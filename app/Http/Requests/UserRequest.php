<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest{
    public function authorize(){
        return true;
    }

    public function rules(){
        if($this->method() == 'PATCH'){
            return [
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => 'required|email|unique:users,email,'.$this->id,
                'contact_number' => 'required|digits:10|unique:users,contact_number,'.$this->id,
                'role' => 'required'
            ];
        }else{
            return [
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => 'required|email|unique:users,email',
                'contact_number' => 'required|digits:10|unique:users,contact_number',
                'role' => 'required'
            ];
        }
    }

    public function messages(){
        return [
            'first_name.required' => 'Please enter first name',
            'first_name.max' => 'Please enter first name maximum 255 characters',
            'last_name.required' => 'Please enter last name',
            'last_name.max' => 'Please enter last name maximum 255 characters',
            'email.required' => 'Please enter email',
            'email.email' => 'Please enter valid email',
            'email.unique' => 'Please enter unique email',
            'contact_number.required' => 'Please enter contact number number',
            'contact_number.digits' => 'Please enter 10 digit number',
            'contact_number.unique' => 'Please enter unique contact number',
            'role.required' => 'Please select role'
        ];
    }
}
