<?php

namespace App\Http\Requests;

use App\Rules\CoordinatesRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class OrderCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Specify if the request should be authorized or not.
        // For example, you can check if the user is authenticated.
        return $this->user !== null;
    }
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Define the validation rules for the request
        return [
            'total_price' => 'decimal:0,4|required',
            'distance' => 'decimal:0,4|required',
            'payment_method' => 'string|required',
            'car_class_id' => 'integer|required',
            'start_street_name' => 'string|required',
            'end_street_name' => 'string|required',
            'start_location' => ['required', new CoordinatesRule()],
            'end_location' => ['required', new CoordinatesRule()],
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        // Create a custom JSON response for validation errors
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
