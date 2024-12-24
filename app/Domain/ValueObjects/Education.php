<?php

namespace App\Domain\ValueObjects;

use App\Domain\Traits\AccessorTrait;
use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;
use Carbon\Carbon;

class Education
{
    use AccessorTrait;

    public function __construct(
        #[Setter] #[Getter]
        private string $degree,
        #[Setter] #[Getter]
        private string $institution,
        #[Setter] #[Getter]
        private string $fieldOfStudy,
        #[Setter] #[Getter]
        private Carbon $startDate,
        #[Setter] #[Getter]
        private Carbon $endDate,
        #[Setter] #[Getter]
        private Carbon $grade
    ) {
    }
}
