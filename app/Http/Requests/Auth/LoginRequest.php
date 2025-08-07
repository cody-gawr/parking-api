<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseApiRequest;

class LoginRequest extends BaseApiRequest
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
            'email' => 'required|string|email',
            'password' => 'required|string',
        ];
    }

    /**
     * Give Scribe explicit descriptions, types & examples.
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
                'description' => 'Valid, unique email address.',
                'example'     => 'jane@example.com',
                'type'        => 'string',
                'required'    => true,
            ],
            'password' => [
                'description' => 'Password (min 8 chars).',
                'type'        => 'string',
                'required'    => true,
            ],
            'password_confirmation' => [
                'description' => 'Must match `password` field.',
                'type'        => 'string',
                'required'    => true,
            ],
        ];
    }
}
