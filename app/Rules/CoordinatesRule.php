<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CoordinatesRule implements Rule
{
    /**
     * Determine if the coordinates are valid.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        // Check if the value is an array with 'latitude' and 'longitude' keys
        if (!is_array($value) || !isset($value['latitude']) || !isset($value['longitude'])) {
            return false;
        }

        // Extract latitude and longitude from the array
        $latitude = $value['latitude'];
        $longitude = $value['longitude'];

        // Check if both latitude and longitude are numeric
        if (!is_numeric($latitude) || !is_numeric($longitude)) {
            return false;
        }

        // Convert latitude and longitude to floats
        $latitude = floatval($latitude);
        $longitude = floatval($longitude);

        // Validate the range of latitude and longitude
        return $latitude >= -90 && $latitude <= 90 &&
            $longitude >= -180 && $longitude <= 180;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The :attribute field must contain valid coordinates (latitude, longitude).';
    }
}
