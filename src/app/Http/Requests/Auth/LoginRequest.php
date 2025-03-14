<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
    *
     * @return array
     */
    public function rules(): array
    {
        return [
            'username' => 'bail|required|string|max:255',
            'password' => 'bail|required|string|max:255',
            // 'token' => 'bail|required|string',
            'action' => 'bail|required|in:login'
        ];
    }
}