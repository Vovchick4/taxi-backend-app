<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class RegisterRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $rules = [
            'isDriver' => 'required|boolean',
            'phone' => 'required|string',
            'city' => 'required|string',
            'name' => 'required|string',
            'surname' => 'required|string',
            'email' => 'nullable|email',
        ];

        // Additional rules if the user is a driver
        if ($this->input('isDriver')) {
            $rules = array_merge($rules, [
                'passport_expiration_date' => 'required|date_format:Y-m-d',
                'passport_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
        }

        return $rules;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->toArray();

        throw new HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => 'Validation errors',
                'errors' => $errors,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
