<?php

namespace App\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'videos'            => 'required|array|min:1',
            'videos.*'          => 'file|mimetypes:video/mp4,video/avi,video/mpeg|max:512000',
            'video_languages'   => 'required|array',
            'video_languages.*' => 'required|string|in:en,es,fr,de,it,pt',
            'cvs'               => 'sometimes|array|min:1',
            'cvs.*'             => 'file|mimetypes:application/pdf|max:10240',
            'languages'         => 'required|array',
            'languages.*'       => 'required|string|in:en,es,fr,de,it,pt',
        ];
    }
}
