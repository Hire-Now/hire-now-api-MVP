<?php

namespace App\Domain\ValueObjects;

use App\Domain\Traits\AccessorTrait;
use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;

class Rate
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private bool $status,
        #[Getter] #[Setter]
        private ?Range $range
    ) {
    }
}
