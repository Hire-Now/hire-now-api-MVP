<?php

namespace App\Domain\ValueObjects;

use App\Domain\Traits\AccessorTrait;
use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;

class ContractType
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private bool $fullTime,
        #[Getter] #[Setter]
        private bool $partTime,
        #[Getter] #[Setter]
        private bool $hourly,
        #[Getter] #[Setter]
        private bool $fixedTerm
    ) {
    }
}
