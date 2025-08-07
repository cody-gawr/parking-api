<?php

namespace App\Http\Requests\Parking;

use App\Http\Requests\BaseApiRequest;

class StoreParkingRequest extends BaseApiRequest
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
            'name'      => 'required|string|max:255',
            'address'   => 'required|string|max:500',
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
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
                'description' => 'The name of the parking lot.',
                'example'     => 'Central Parking',
                'type'        => 'string',
                'required'    => true,
            ],
            'address' => [
                'description' => 'The address of the parking lot.',
                'example'     => '123 Main St, Springfield',
                'type'        => 'string',
                'required'    => true,
            ],
            'latitude' => [
                'description' => 'Latitude coordinate between -90 and 90.',
                'example'     => 40.7128,
                'type'        => 'number',
                'required'    => true,
            ],
            'longitude' => [
                'description' => 'Longitude coordinate between -180 and 180.',
                'example'     => -74.0060,
                'type'        => 'number',
                'required'    => true,
            ],
        ];
    }
}
