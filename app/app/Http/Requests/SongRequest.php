<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SongRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->path() == 'songs' || 'songs/{song}') {
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
            'name' => 'required|unique:songs,name,' . $this->input('id'). ',id,artist_id,' . $this->input('artist_id'),
            'artist_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => ':attributeは必須項目です',
            'name.unique' => '既に登録されています',
            'artist_id.required' => ':attributeを選択してください',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '曲名',
            'artist_id' => 'アーティスト名',
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
