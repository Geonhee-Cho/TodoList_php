<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
            'user_id' => ['bail', 'required', 'min:5', 'max:20'],
            'password' => ['bail', 'required', 'min:8', 'max:20'],
            'confirm_password' => ['bail', 'required', 'min:8', 'max:20'],
            'name' => ['bail', 'required', 'min:2', 'max:10'],
        ];
    }

    public function messages()
    {
        return [
            'user_id.reuired' => 'ID를 입력해주세요.',
            'user_id.min:5' => 'ID는 최소 5자 입니다.',
            'user_id.max:20' => 'ID는 최대 20자 입니다.',
            'password.reuired' => 'PW를 입력해주세요.',
            'password.min:5' => 'PW는 최소 8자 입니다.',
            'password.max:20' => 'PW는 최대 20자 입니다.',
            'confirm_password.reuired' => 'Confirm PW를 입력해주세요.',
            'confirm_password.min:5' => 'Confirm PW는 최소 8자 입니다.',
            'confirm_password.max:20' => 'Confirm PW는 최대 20자 입니다.',
            'name.reuired' => 'NAME을 입력해주세요.',
            'name.min:5' => 'NAME을 최소 2자 입니다.',
            'name.max:20' => 'NAME을 최대 10자 입니다.',
        ];
    }
}
