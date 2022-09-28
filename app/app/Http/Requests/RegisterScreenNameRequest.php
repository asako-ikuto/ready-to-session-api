<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterScreenNameRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->path() == 'register-screen-name') {
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
            'screen_name' => 'required|string|max:15|min:4|unique:users',
        ];
    }

    public function messages()
    {
        return [
            'screen_name.required' => ':attributeは必須項目です',
            'screen_name.string' => ':attributeは文字を入力してください',
            'screen_name.max' => ':attributeは15文字以内です',
            'screen_name.min' => ':attributeは4文字以上です',
            'screen_name.unique' => '別の:attributeを入力してください',
        ];
    }

    public function attributes()
    {
        return [
            'screen_name' => 'ユーザID',
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
