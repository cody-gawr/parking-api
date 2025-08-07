<?php

namespace App\Http\Requests\Parking;

use App\Http\Requests\BaseApiRequest;

class ClosestParkingRequest extends BaseApiRequest
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
            'latitude' => 'required|numeric|between:-90,90',
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
            'latitude' => [
                'description' => 'Latitude of the point to search from.',
                'example'     => 40.7128,
                'type'        => 'number',
                'required'    => true,
            ],
            'longitude' => [
                'description' => 'Longitude of the point to search from.',
                'example'     => -74.0060,
                'type'        => 'number',
                'required'    => true,
            ],
        ];
    }
}
