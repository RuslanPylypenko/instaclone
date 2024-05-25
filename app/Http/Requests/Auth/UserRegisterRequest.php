<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'nullable|string|max:255',
            'nick'       => 'required|string|max:255|unique:users',
            'email'      => 'required|string|email|unique:users',
            'password'   => 'required|min:6',
            'birth_date' => 'required|date|date_format:Y-m-d',
        ];
    }
}
