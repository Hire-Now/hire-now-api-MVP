<?php

namespace App\Domain\ValueObjects;

use App\Domain\Traits\AccessorTrait;
use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;

class CompletedAssesments
{
    use AccessorTrait;

    public function __construct(
        #[Setter] #[Getter]
        private string $assesment,
        #[Setter] #[Getter]
        private bool $completed,
        #[Setter] #[Getter]
        private string $grade
    ) {
    }
}
