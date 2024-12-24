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
        /** @var Education[]*/
        private array $cvUrl,
    ) {}
}
