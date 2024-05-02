<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendCodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Specify if the request should be authorized or not.
        // For example, you can check if the user is authenticated.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Define the validation rules for the request
        return [
            'phone' => 'required|string', // Add other rules as needed
        ];
    }

    /**
     * Customize the validation response to return errors in JSON format.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return \Illuminate\Http\JsonResponse
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        // Create a custom JSON response for validation errors
        $errors = $validator->errors();

        return response()->json([
            'status' => 'error',
            'message' => 'Validation errors',
            'errors' => $errors
        ], 422); // HTTP status code 422 for validation errors
    }
}
