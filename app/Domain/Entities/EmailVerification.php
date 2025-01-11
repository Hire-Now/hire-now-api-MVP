<?php

namespace App\Domain\Entities;

use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;
use App\Domain\Traits\AccessorTrait;
use Carbon\Carbon;

class EmailVerification
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private ?string $id,
        #[Getter] #[Setter]
        private ?string $userId,
        #[Getter] #[Setter]
        private ?string $email,
        #[Getter] #[Setter]
        private ?string $storedHash,
        #[Getter] #[Setter]
        private ?string $urlHash,
        #[Getter] #[Setter]
        private ?string $verifyLink,
        #[Getter] #[Setter]
        private ?Carbon $emailVerifiedAt
    ) {
    }

    public function toArray(): array
    {
        return [
            'id'              => $this->id,
            'userId'          => $this->userId,
            'email'           => $this->email,
            'hash'            => $this->urlHash,
            'verifyLink'      => $this->verifyLink,
            'emailVerifiedAt' => $this->emailVerifiedAt?->toDateTimeString(),
        ];
    }
}
