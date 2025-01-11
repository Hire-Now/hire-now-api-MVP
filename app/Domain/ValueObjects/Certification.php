<?php

namespace App\Domain\ValueObjects;

use App\Domain\Traits\AccessorTrait;
use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;

class Certification
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private ?string $name,
        #[Getter] #[Setter]
        private ?string $issueDate,
        #[Getter] #[Setter]
        private ?string $issuerEntity,
        #[Getter] #[Setter]
        private ?string $expiryDate,
        #[Getter] #[Setter]
        private ?string $link
    ) {
    }

    public function toArray(): array
    {
        return [
            'name'          => $this->name,
            'issue_date'    => $this->issueDate,
            'issuer_entity' => $this->issuerEntity,
            'expiry_date'   => $this->expiryDate,
            'link'          => $this->link,
        ];
    }
}
