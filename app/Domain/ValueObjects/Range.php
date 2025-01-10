<?php

namespace App\Domain\ValueObjects;

use App\Domain\Traits\AccessorTrait;
use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;

class Range
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private float $min,
        #[Getter] #[Setter]
        private float $max
    ) {
    }
}
