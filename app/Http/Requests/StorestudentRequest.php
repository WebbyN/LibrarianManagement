<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorestudentRequest extends FormRequest
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
            'name' => "required|regex:/^[\pL\s\-]+$/u",
            'address' => "required",
            'gender' => "required|alpha",
            'class' => "required|regex:/^[\pL\s\-]+$/u",
            'age' => "required|numeric",
            'phone' => "required|numeric",
            'email' => "required|email",
        ];
    }
}
