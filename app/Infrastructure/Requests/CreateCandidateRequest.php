<?php

namespace App\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCandidateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Cambia esto si necesitas autorización para este request
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'skills'                => 'required|array|min:1',
            'skills.*'              => 'string',
            'languages'             => 'required|array|min:1',
            'languages.*'           => 'string',
            'yearsOfExperience'     => 'required|integer|min:0',
            'previousExperiences'   => 'required|array|min:1',
            'previousExperiences.*' => 'json',
            'education'             => 'required|array|min:1',
            'education.*'           => 'json',
            'uploadedCV'            => 'nullable|array|min:1',
            'uploadedCV.*'          => 'uuid|string',
            'uploadedPitch'         => 'nullable|array|min:1',
            'uploadedPitch.*'       => 'uuid|string',
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'userId.required'                 => 'El ID de usuario es obligatorio.',
            'skills.required'                 => 'Las habilidades son obligatorias.',
            'languages.required'              => 'Los lenguajes son obligatorios.',
            'yearsOfExperience.required'      => 'Los años de experiencia son obligatorios.',
            'previousExperiences.required'    => 'Las experiencias previas son obligatorias.',
            'education.required'              => 'La educación es obligatoria.',
            'uploadedCV.file'                 => 'El CV debe ser un archivo.',
            'uploadedPitch.array'             => 'El pitch debe ser un arreglo de archivos.',
            'languagesGrades.*.grade.between' => 'La calificación debe estar entre 1 y 10.',
        ];
    }
}
