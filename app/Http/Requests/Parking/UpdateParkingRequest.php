<?php

namespace App\Http\Requests\Parking;

use App\Http\Requests\BaseApiRequest;

class UpdateParkingRequest extends BaseApiRequest
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
            'name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:255',
            'latitude' => 'sometimes|required|numeric|between:-90,90',
            'longitude' => 'sometimes|required|numeric|between:-180,180'
        ];
    }

    /**
     * Explicit request body metadata for Scribe.
     * Fields are optional; include only the ones you wish to update.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'New name for the parking lot (optional).',
                'example'     => 'Updated Parking Name',
                'type'        => 'string',
                'required'    => false,
            ],
            'address' => [
                'description' => 'New address for the parking lot (optional).',
                'example'     => '456 Elm St, Springfield',
                'type'        => 'string',
                'required'    => false,
            ],
            'latitude' => [
                'description' => 'New latitude coordinate (optional).',
                'example'     => 40.7138,
                'type'        => 'number',
                'required'    => false,
            ],
            'longitude' => [
                'description' => 'New longitude coordinate (optional).',
                'example'     => -74.0055,
                'type'        => 'number',
                'required'    => false,
            ],
        ];
    }
}
