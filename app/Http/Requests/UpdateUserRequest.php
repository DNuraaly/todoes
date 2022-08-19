<?php

namespace App\Http\Requests;

use Illuminate\Http\File;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' =>              ['required', 'string', 'max:255'],
            'profile_photo' =>     ['nullable',
                                    'image',
                                    'mimes:jpg,jpeg,png,gif',
                                    'max:1024'
                ]
        ];
    }
}
