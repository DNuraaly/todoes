<?php

namespace App\Http\Requests;


class LogInRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' =>      ['required', 'string', 'email:filter'],
            'password' =>   ['required', 'string','confirm']
        ];
    }

}
