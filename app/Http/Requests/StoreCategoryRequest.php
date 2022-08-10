<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    /**
     * @param Validator $validator
     * @return Void
     */
    protected function failedValidation(Validator $validator)
    {
//        $this->validator = $validator;
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
            'title' => [
                'required',
                'string',
                'max:30',
                Rule::unique('categories', 'title')
                    ->where('user_id', request()->user()->id)
            ]
        ];
    }

    /**
     * @return Validator
     */
    public function getValidator(): Validator
    {
        return $this->validator;
    }
}
