<?php

namespace App\Application\Commands\Candidate;

use App\Domain\Traits\AccessorTrait;
use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;

class CreateCandidateCommand
{
    use AccessorTrait;

    public function __construct(
        /** @var Skill[] */
        #[Getter] #[Setter]
        private array $skills,
        /** @var Language[]*/
        #[Getter] #[Setter]
        private array $languages,
        private int $yearsOfExperience,
        /** @var PreviousExperiences[]*/
        #[Getter] #[Setter]
        private array $previousExperiences,
        /** @var Education[]*/
        #[Getter] #[Setter]
        private array $education,
        #[Getter] #[Setter]
        private ?string $uploadedCV
    ) {}
}
