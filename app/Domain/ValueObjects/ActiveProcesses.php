<?php

namespace App\Domain\ValueObjects;

use App\Domain\Traits\AccessorTrait;
use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;

class ActiveProcesses
{
    use AccessorTrait;

    public function __construct(
        #[Setter] #[Getter]
        private string $offerId,
        #[Setter] #[Getter]
        private string $status
    ) {
    }
}
