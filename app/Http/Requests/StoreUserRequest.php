<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends BaseRequest
{

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
            'password' =>   ['required', 'string','confirmed','same:password'],
        ];
    }

}
