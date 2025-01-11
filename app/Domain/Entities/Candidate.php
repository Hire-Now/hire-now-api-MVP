<?php

namespace App\Domain\Entities;

use App\Domain\Traits\AccessorTrait;
use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;
use App\Domain\ValueObjects\Benefits;
use App\Domain\ValueObjects\Certification;
use App\Domain\ValueObjects\ContactInfo;
use App\Domain\ValueObjects\ContractModel;
use App\Domain\ValueObjects\ContractType;
use App\Domain\ValueObjects\Education;
use App\Domain\ValueObjects\Language;
use App\Domain\ValueObjects\Milestone;
use App\Domain\ValueObjects\Payment;
use App\Domain\ValueObjects\PortfolioLinks;
use App\Domain\ValueObjects\Preferences;
use App\Domain\ValueObjects\PreviousExperience;
use App\Domain\ValueObjects\Range;
use App\Domain\ValueObjects\Rate;
use App\Domain\ValueObjects\Skill;
use App\Domain\ValueObjects\SocialMedia;
use App\Domain\ValueObjects\WorkModel;
use Carbon\Carbon;

class Candidate
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private ?string $id,
        #[Getter] #[Setter]
        private ?string $userId,
        /** @var Skill[] */
        #[Getter]
        private ?array $skills = null,
        /** @var Language[]*/
        #[Getter]
        private ?array $languages = null,
        #[Getter] #[Setter]
        private ?string $yearsOfExperience = null,
        /** @var PreviousExperience[]*/
        #[Getter]
        private ?array $previousExperiences = null,
        #[Getter]
        /** @var Education[]*/
        private ?array $education = null,
        #[Getter] #[Setter]
        private ?string $professionalSummary = null,
        /** @var Certification[]*/
        #[Getter]
        private ?array $certifications = null,
        #[Getter]
        private ?ContactInfo $contactInfo = null,
        #[Getter]
        private ?PortfolioLinks $portfolioLinks = null,
        #[Getter]
        private ?Preferences $preferences = null,
        /** @var Curriculum[]*/
        #[Getter] #[Setter]
        private ?string $uploadedCV = null,
        #[Getter] #[Setter]
        private ?array $uploadedPitch = null,
        /** @var LanguagesGrades[]*/
        #[Getter] #[Setter]
        private ?array $languagesGrades = null,
        /** @var TechnicalGrades[]*/
        #[Getter] #[Setter]
        private ?array $technicalGrades = null,
        /** @var CompletedAssesments[]*/
        #[Getter] #[Setter]
        private ?array $completedAssesments = null,
        /** @var ActiveProcesses[]*/
        #[Getter] #[Setter]
        private ?array $activeProcesses = null,
        #[Getter] #[Setter]
        private ?string $generatedPlatformCV = null,
    ) {
    }

    public function setSkills(array $skills): void
    {
        $this->skills = collect($skills)->map(function ($skill) {
            return new Skill($skill['name'], $skill['level']);
        })->toArray();
    }

    public function setLanguages(array $languages): void
    {
        $this->languages = collect($languages)->map(function ($language) {
            return new Language($language['name'], $language['level']);
        })->toArray();
    }

    public function setPreviousExperiences(array $previousExperiences): void
    {
        $this->previousExperiences = collect($previousExperiences)->map(function ($previousExperience): PreviousExperience {
            return new PreviousExperience(
                $previousExperience['company_name'] ?? '',
                $previousExperience['role'] ?? '',
                new Carbon($previousExperience['start_date']),
                new Carbon($previousExperience['end_date']) ?? null,
                $previousExperience['description'] ?? '',
                $previousExperience['technologies'] ?? [],
                collect($previousExperience['milestones'] ?? [])->map(function ($milestone): Milestone {
                    return new Milestone(
                        $milestone['name'] ?? '',
                        $milestone['description'] ?? '',
                        $milestone['technologies'] ?? [],
                        $milestone['role'] ?? '',
                        $milestone['outcome'] ?? ''
                    );
                })->toArray()
            );
        })->toArray();
    }

    public function setEducation(array $educations): void
    {
        $this->education = collect($educations ?? [])->map(function ($educations): Education {
            return new Education(
                $educations['degree'],
                $educations['institution'],
                $educations['field_of_study'],
                new Carbon($educations['start_date']),
                new Carbon($educations['end_date']),
                $educations['grade'] ?? null
            );
        })->toArray();
    }

    public function setCertifications(array $certifications): void
    {
        $this->certifications = collect($certifications ?? [])->map(function ($certificate): Certification {
            return new Certification(
                $certificate['name'],
                $certificate['issue_date'],
                $certificate['issuer_entity'],
                $certificate['expiry_date'],
                $certificate['link']
            );
        })->toArray();
    }

    public function setContactInfo(array $contactInformation): void
    {
        $this->contactInfo = new ContactInfo(
            $contactInformation['phone_number'],
            $contactInformation['whatsapp_number'],
            $contactInformation['email'],
            $contactInformation['residence_country'],
            $contactInformation['residence_address'],
            new SocialMedia(
                $contactInformation['social_medial']['facebook'],
                $contactInformation['social_medial']['instagram'],
                $contactInformation['social_medial']['twitter'],
                $contactInformation['social_medial']['linkedin']
            )
        );
    }

    public function setPortfolioLinks(array $portfolioLinks): void
    {
        $this->portfolioLinks = new PortfolioLinks(
            $portfolioLinks['github'],
            $portfolioLinks['hackerrank'],
            $portfolioLinks['dribbble'],
            $portfolioLinks['gitlab'],
            $portfolioLinks['behance'],
            $portfolioLinks['leetcode'],
            $portfolioLinks['customized_projects'],
        );
    }

    public function setPreferences(array $preferences): void
    {
        $workModelData = $preferences['work_model'];
        $contractModelData = $preferences['contract_model'];

        $workModel = new WorkModel(
            $workModelData['remote'],
            $workModelData['hybrid'],
            $workModelData['on-site']
        );

        $contractTypeData = $contractModelData['type'];
        $contractType = new ContractType(
            $contractTypeData['full_time'],
            $contractTypeData['part_time'],
            $contractTypeData['hourly'],
            $contractTypeData['fixed_term']
        );

        $hourlyRateData = $contractModelData['payment']['hourly_rate'];
        $hourlyRate = new Rate(
            $hourlyRateData['status'],
            new Range($hourlyRateData['range']['min'], $hourlyRateData['range']['max'])
        );

        $monthlyFixedData = $contractModelData['payment']['monthly_fixed'];
        $monthlyFixed = new Rate(
            $monthlyFixedData['status'],
            new Range($monthlyFixedData['range']['min'], $monthlyFixedData['range']['max'])
        );

        $paymentData = $contractModelData['payment'];
        $payment = new Payment(
            $paymentData['currency'],
            $hourlyRate,
            $monthlyFixed,
            $paymentData['project_fixed']
        );

        $benefitsData = $contractModelData['benefits'];
        $benefits = new Benefits(
            $benefitsData['health_insurance'],
            $benefitsData['paid_time_off'],
            $benefitsData['retirement_plan']
        );

        $contractModel = new ContractModel($contractType, $payment, $benefits);

        $this->preferences = new Preferences($workModel, $contractModel);
    }

    public function toArray(): array
    {
        return [
            'id'                   => $this->id,
            'user_id'              => $this->userId,
            'skills'               => array_map(fn(Skill $skill) => $skill->toArray(), $this->skills ?? []),
            'languages'            => array_map(fn(Language $language) => $language->toArray(), $this->languages ?? []),
            'years_of_experience'  => $this->yearsOfExperience,
            'previous_experiences' => array_map(fn(PreviousExperience $experience) => $experience->toArray(), $this->previousExperiences ?? []),
            'education'            => array_map(fn(Education $education) => $education->toArray(), $this->education ?? []),
            'professional_summary' => $this->professionalSummary,
            'certifications'       => array_map(fn(Certification $certification) => $certification->toArray(), $this->certifications ?? []),
            'contact_info'         => $this->contactInfo?->toArray(),
            'portfolio_links'      => $this->portfolioLinks?->toArray(),
            'preferences'          => $this->preferences?->toArray(),
            // 'uploaded_cv'          => $this->uploadedCV,
            // 'uploaded_pitch'       => $this->uploadedPitch,
            // 'languages_grades'      => array_map(fn(LanguagesGrades $grade) => $grade->toArray(), $this->languagesGrades ?? []),
            // 'technical_grades'      => array_map(fn(TechnicalGrades $grade) => $grade->toArray(), $this->technicalGrades ?? []),
            // 'completed_assessments' => array_map(fn(CompletedAssesments $assessment) => $assessment->toArray(), $this->completedAssesments ?? []),
            // 'active_processes'      => array_map(fn(ActiveProcesses $process) => $process->toArray(), $this->activeProcesses ?? []),
            // 'generated_platform_cv' => $this->generatedPlatformCV,
        ];
    }
}
