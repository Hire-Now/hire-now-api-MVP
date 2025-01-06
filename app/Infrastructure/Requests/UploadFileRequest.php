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
            'videos'            => 'nullable|array',
            'videos.*'          => 'file|mimes:mp4,avi,mpeg|max:512000',
            'video_languages'   => 'required_with:videos|array|size:' . (count($this->file('videos') ?? [])),
            'video_languages.*' => 'in:en,es,fr,de,it,pt',
            'cvs'               => 'required|array|min:1',
            'cvs.*'             => 'file|mimes:pdf|max:10240',
            'languages'         => 'required_with:cvs.*|array|size:' . (count($this->file('cvs') ?? [])),
            'languages.*'       => 'in:en,es,fr,de,it,pt',
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
