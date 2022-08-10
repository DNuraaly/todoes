<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
//        parent::failedValidation($validator); // TODO: Change the autogenerated stub
    }

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
            'name' =>       ['required', 'string', 'max:255'],
            'email' =>      ['required', 'string', 'unique:users,email', 'email:filter'],
            'password' =>   ['required', 'string']
        ];
    }

    public function getValidator()
    {
        return $this->validator;
    }
}