<?php

namespace App\Http\Requests\Amsl;

use Illuminate\Foundation\Http\FormRequest;

class UserInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {


        return [
           'name' => 'required|min:3',//|unique_with:users,name,ignore:'.$this->route('user_info'),
           'email'=>'required|min:3',//|unique_with:users,email,ignore:'.$this->route('user_info'),

        ];
    }
}
