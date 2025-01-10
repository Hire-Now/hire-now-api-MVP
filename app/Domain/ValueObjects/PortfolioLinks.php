<?php

namespace App\Domain\ValueObjects;

use App\Domain\Traits\AccessorTrait;
use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;

class PortfolioLinks
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private ?string $github,
        #[Getter] #[Setter]
        private ?string $hackerrank,
        #[Getter] #[Setter]
        private ?string $dribbble,
        #[Getter] #[Setter]
        private ?string $gitlab,
        #[Getter] #[Setter]
        private ?string $behance,
        #[Getter] #[Setter]
        private ?string $leetcode,
        #[Getter] #[Setter]
        private array $customizedProjects
    ) {
    }
}
