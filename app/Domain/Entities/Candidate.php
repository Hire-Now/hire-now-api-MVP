<?php

namespace App\Domain\Entities;

use App\Domain\Traits\AccessorTrait;
use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;
use App\Domain\ValueObjects\Skill;

class Candidate
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private ?int $userId,
        /** @var Skill[] */
        #[Getter] #[Setter]
        private array $skills,
        /** @var Language[]*/
        #[Getter] #[Setter]
        private array $languages,
        #[Getter] #[Setter]
        private int $yearsOfExperience,
        /** @var PreviousExperiences[]*/
        #[Getter] #[Setter]
        private array $previousExperiences,
        #[Getter] #[Setter]
        /** @var Education[]*/
        private array $education,
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
    ) {}
}
