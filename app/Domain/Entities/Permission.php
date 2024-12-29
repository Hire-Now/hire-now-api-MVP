<?php

namespace App\Domain\Entities;

use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;
use App\Domain\Traits\AccessorTrait;

class Permission
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private string $name,
        #[Getter] #[Setter]
        private string $description,
    ) {
    }
}

