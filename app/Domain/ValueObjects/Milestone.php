<?php

namespace App\Domain\ValueObjects;

use App\Domain\Traits\AccessorTrait;
use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;

class Milestone
{
    use AccessorTrait;

    public function __construct(
        #[Setter] #[Getter]
        private string $name,
        #[Setter] #[Getter]
        private string $description,
        #[Setter] #[Getter]
        private array $technologies,
        #[Setter] #[Getter]
        private string $role,
        #[Setter] #[Getter]
        private string $outcome,
    ) {
    }
}
