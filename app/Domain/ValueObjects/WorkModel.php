<?php

namespace App\Domain\ValueObjects;

use App\Domain\Traits\AccessorTrait;
use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;

class WorkModel
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private bool $remote,
        #[Getter] #[Setter]
        private bool $hybrid,
        #[Getter] #[Setter]
        private bool $onSite
    ) {
    }
}
