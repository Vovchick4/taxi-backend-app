<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyCodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone' => 'required',
            'code' => 'required|min:4|max:4',
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
