<?php

namespace App\Domain\ValueObjects;

use App\Domain\Traits\AccessorTrait;
use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;

class ContactInfo
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private ?string $phoneNumber,
        #[Getter] #[Setter]
        private ?string $whatsappNumber,
        #[Getter] #[Setter]
        private ?string $email,
        #[Getter] #[Setter]
        private ?string $residenceCountry,
        #[Getter] #[Setter]
        private ?string $residenceAddress,
        #[Getter] #[Setter]
        private SocialMedia $socialMedia
    ) {
    }
}
