<?php

namespace App\Domain\ValueObjects;

use App\Domain\Traits\AccessorTrait;
use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;

class Language
{
    use AccessorTrait;

    public function __construct(
        #[Setter] #[Getter]
        private string $language,
        #[Setter] #[Getter]
        private string $proficiency
    ) {}
}
