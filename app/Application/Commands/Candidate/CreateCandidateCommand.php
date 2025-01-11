<?php

namespace App\Application\Commands\Candidate;

use App\Domain\Traits\AccessorTrait;
use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;

class CreateCandidateCommand
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private string $userId,
        #[Getter] #[Setter]
        private ?array $skills,
        #[Getter] #[Setter]
        private ?array $languages,
        #[Getter] #[Setter]
        private ?array $previousExperiences,
        #[Getter] #[Setter]
        private ?array $education,
        #[Getter] #[Setter]
        private ?string $professionalSummary,
        #[Getter] #[Setter]
        private ?array $certifications,
        #[Getter] #[Setter]
        private ?array $contactInfo,
        #[Getter] #[Setter]
        private ?array $portfolioLinks,
        #[Getter] #[Setter]
        private ?array $preferences,

    ) {
    }
}
