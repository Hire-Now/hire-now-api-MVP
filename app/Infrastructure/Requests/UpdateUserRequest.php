<?php

namespace App\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'name'       => 'sometimes|required|string|max:255',
            'email'      => [
                'sometimes',
                'required',
                'email:rfc,dns',
                Rule::unique('users')->ignore($this->route('user')),
            ],
            'password'   => 'sometimes|nullable|confirmed|min:8',
            'birth_date' => 'sometimes|required|date',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        $response = [
            'status'  => 'ERROR',
            'message' => 'Validation failed.',
            'errors'  => $errors->toArray(),
        ];

        throw new HttpResponseException(response()->json($response, 422));
    }
}
