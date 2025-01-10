<?php

namespace App\Domain\ValueObjects;

use App\Domain\Traits\AccessorTrait;
use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;

class SocialMedia
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private ?string $facebook,
        #[Getter] #[Setter]
        private ?string $instagram,
        #[Getter] #[Setter]
        private ?string $twitter,
        #[Getter] #[Setter]
        private ?string $linkedin
    ) {
    }
}
