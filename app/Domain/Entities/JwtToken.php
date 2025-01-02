<?php

namespace App\Domain\Entities;

use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;
use App\Domain\Traits\AccessorTrait;

class JwtToken
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private ?string $id,
        #[Getter] #[Setter]
        private ?string $userId,
        #[Getter] #[Setter]
        private ?string $jti,
        #[Getter] #[Setter]
        private ?string $expiryTime,
        #[Getter] #[Setter]
        private ?string $typeTime,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id'         => $this->id,
            'userId'     => $this->userId,
            'jti'        => $this->jti,
            'expiryTime' => $this->expiryTime,
            'typeTime'   => $this->typeTime,
        ];
    }
}

