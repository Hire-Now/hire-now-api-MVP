<?php

namespace App\Infrastructure\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UploadFileRequest extends FormRequest
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
            'files'              => 'required|array|min:1',
            'files.*.file'       => 'required|file|max:512000|mimes:mp4,avi,mpeg,pdf',
            'files.*.language'   => 'required|in:en,es,fr,de,it,pt',
            'files.*.visibility' => 'required|in:public,private',
            'files.*.type'       => 'required|in:video,pdf',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  Validator  $validator
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
