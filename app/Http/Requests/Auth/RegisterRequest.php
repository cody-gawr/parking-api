<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseApiRequest;

class RegisterRequest extends BaseApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    /**
     * Explicit request body metadata for Scribe.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'Full name of the user.',
                'example'     => 'Jane Doe',
                'type'        => 'string',
                'required'    => true,
            ],
            'email' => [
                'description' => 'Valid, unique email address for the user.',
                'example'     => 'jane.doe@example.com',
                'type'        => 'string',
                'required'    => true,
            ],
            'password' => [
                'description' => 'Password (minimum 8 characters).',
                'example'     => 'secretPass123',
                'type'        => 'string',
                'required'    => true,
            ],
            'password_confirmation' => [
                'description' => 'Confirmation of the password; must match the `password` field.',
                'example'     => 'secretPass123',
                'type'        => 'string',
                'required'    => true,
            ],
        ];
    }
}
