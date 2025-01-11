<?php

namespace App\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'professional_summary'                                       => 'required|string|max:1000',
            'previous_experiences'                                       => 'required|array|min:1',
            'previous_experiences.*.company_name'                        => 'required|string|max:255',
            'previous_experiences.*.role'                                => 'required|string|max:255',
            'previous_experiences.*.start_date'                          => 'required|date_format:d-m-Y',
            'previous_experiences.*.end_date'                            => 'nullable|date_format:d-m-Y|after_or_equal:previous_experiences.*.startDate',
            'previous_experiences.*.description'                         => 'required|string|max:500',
            'previous_experiences.*.technologies'                        => 'required|array|min:1',
            'previous_experiences.*.technologies.*'                      => 'required|string|max:100',
            'previous_experiences.*.milestones'                          => 'required|array|min:1',
            'previous_experiences.*.milestones.*.name'                   => 'required|string|max:255',
            'previous_experiences.*.milestones.*.description'            => 'required|string|max:500',
            'previous_experiences.*.milestones.*.technologies'           => 'required|array|min:1',
            'previous_experiences.*.milestones.*.technologies.*'         => 'required|string|max:100',
            'previous_experiences.*.milestones.*.role'                   => 'required|string|max:255',
            'previous_experiences.*.milestones.*.outcome'                => 'required|string|max:1000',
            'skills'                                                     => 'required|array|min:1',
            'skills.*.name'                                              => 'required|string|max:100',
            'skills.*.level'                                             => 'required|numeric|min:0|max:10',
            'education'                                                  => 'required|array|min:1',
            'education.*.degree'                                         => 'required|string|max:255',
            'education.*.institution'                                    => 'required|string|max:255',
            'education.*.field_of_study'                                 => 'nullable|string|max:255',
            'education.*.start_date'                                     => 'nullable|date_format:d-m-Y',
            'education.*.end_date'                                       => 'nullable|date_format:d-m-Y|after_or_equal:education.*.startDate',
            'education.*.grade'                                          => 'nullable|string|max:100',
            'languages'                                                  => 'required|array|min:1',
            'languages.*.name'                                           => 'required|string|max:100',
            'languages.*.level'                                          => 'required|numeric|min:0|max:10',
            'certifications'                                             => 'nullable|array',
            'certifications.*.name'                                      => 'nullable|string|max:255',
            'certifications.*.issue_date'                                => 'nullable|date_format:d-m-Y',
            'certifications.*.issuer_entity'                             => 'nullable|string|max:255',
            'certifications.*.expiry_date'                               => 'nullable|date_format:d-m-Y|after_or_equal:certifications.*.issue_date',
            'certifications.*.link'                                      => 'nullable|string|max:255',
            'contact_info.phone_number'                                  => 'nullable|string|max:20',
            'contact_info.whatsapp_number'                               => 'nullable|string|max:20',
            'contact_info.email'                                         => 'nullable|email|max:255',
            'contact_info.residence_country'                             => 'nullable|string|max:255',
            'contact_info.residence_address'                             => 'nullable|string|max:255',
            'contact_info.social_medial.facebook'                        => 'nullable|url|max:255',
            'contact_info.social_medial.instagram'                       => 'nullable|url|max:255',
            'contact_info.social_medial.twitter'                         => 'nullable|url|max:255',
            'contact_info.social_medial.linkedin'                        => 'nullable|url|max:255',
            'preferences.work_model.remote'                              => 'required|boolean',
            'preferences.work_model.hybrid'                              => 'required|boolean',
            'preferences.work_model.on-site'                             => 'required|boolean',
            'preferences.contract_model.type.full_time'                  => 'required|boolean',
            'preferences.contract_model.type.part_time'                  => 'required|boolean',
            'preferences.contract_model.type.hourly'                     => 'required|boolean',
            'preferences.contract_model.type.fixed_term'                 => 'required|boolean',
            'preferences.contract_model.payment.currency'                => 'required|string|max:10',
            'preferences.contract_model.payment.hourly_rate.status'      => 'required|boolean',
            'preferences.contract_model.payment.hourly_rate.range.min'   => 'required|numeric|min:0',
            'preferences.contract_model.payment.hourly_rate.range.max'   => 'required|numeric|gte:preferences.contract_model.payment.hourly_rate.range.min',
            'preferences.contract_model.payment.monthly_fixed.status'    => 'required|boolean',
            'preferences.contract_model.payment.monthly_fixed.range.min' => 'required|numeric|min:0',
            'preferences.contract_model.payment.monthly_fixed.range.max' => 'required|numeric|gte:preferences.contract_model.payment.monthly_fixed.range.min',
            'preferences.contract_model.payment.project_fixed'           => 'required|boolean',
            'preferences.contract_model.benefits.health_insurance'       => 'required|boolean',
            'preferences.contract_model.benefits.paid_time_off'          => 'required|boolean',
            'preferences.contract_model.benefits.retirement_plan'        => 'required|boolean',
            'portfolio_links.github'                                     => 'nullable|url|max:255',
            'portfolio_links.hackerrank'                                 => 'nullable|url|max:255',
            'portfolio_links.dribbble'                                   => 'nullable|url|max:255',
            'portfolio_links.gitlab'                                     => 'nullable|url|max:255',
            'portfolio_links.behance'                                    => 'nullable|url|max:255',
            'portfolio_links.leetcode'                                   => 'nullable|url|max:255',
            'portfolio_links.customized_projects'                        => 'nullable|array',
            'portfolio_links.customized_projects.*'                      => 'nullable|url|max:255',
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
