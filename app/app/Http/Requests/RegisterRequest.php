<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->path() == 'register') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:50',
            'screen_name' => 'required|string|max:15|min:4|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|max:32'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => ':attributeは必須項目です',
            'screen_name.required' => ':attributeは必須項目です',
            'email.required' => ':attributeは必須項目です',
            'password.required' => ':attributeは必須項目です',

            'name.string' => ':attributeは文字を入力してください',
            'screen_name.string' => ':attributeは文字を入力してください',
            'password.string' => ':attributeは文字を入力してください',

            'name.max' => ':attributeは50文字以内です',
            'screen_name.max' => ':attributeは15文字以内です',
            'password.max' => ':attributeは15文字以内です',
            'screen_name.min' => ':attributeは4文字以上です',
            'password.min' => ':attributeは8文字以上です',

            'screen_name.unique' => ':attributeは既に登録されています',
            'email.unique' => ':attributeは既に登録されています',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'ユーザ名',
            'screen_name' => 'ユーザID',
            'email' => 'Email',
            'password' => 'Password',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response['status']  = 400;
        $response['statusText'] = 'Failed validation.';
        $response['errors']  = $validator->errors();
        throw new HttpResponseException(
            response()->json( $response, 200 )
        );
    }
}
