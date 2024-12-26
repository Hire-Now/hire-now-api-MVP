<?php

namespace App\Application\Commands\Candidate;

use App\Domain\Traits\AccessorTrait;
use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;

class UpdateCandidateCommand
{

    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private string $candidateId,
        /** @var Skill[] */
        #[Getter] #[Setter]
        private ?array $skills,
        /** @var Language[]*/
        #[Getter] #[Setter]
        private ?array $languages,
        private ?int $yearsOfExperience,
        /** @var PreviousExperiences[]*/
        #[Getter] #[Setter]
        private ?array $previousExperiences,
        /** @var Education[]*/
        #[Getter] #[Setter]
        private ?array $education,
        #[Getter] #[Setter]
        private ?string $uploadedCV,
        #[Getter] #[Setter]
        private ?array $uploadedPitch,
        /** @var LanguagesGrades[]*/
        #[Getter] #[Setter]
        private ?array $languagesGrades,
        /** @var TechnicalGrades[]*/
        #[Getter] #[Setter]
        private ?array $technicalGrades,
        /** @var CompletedAssesments[]*/
        #[Getter] #[Setter]
        private ?array $completedAssesments,
        /** @var ActiveProcesses[]*/
        #[Getter] #[Setter]
        private ?array $activeProcesses,
        #[Getter] #[Setter]
        private ?string $generatedPlatformCV,
    ) {}
}
