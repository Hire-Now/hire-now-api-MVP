<?php

namespace App\Domain\Entities;

use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;
use App\Domain\Enums\ElementStatus;
use App\Domain\Enums\Roles;
use App\Domain\Traits\AccessorTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class User
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private ?string $id,
        #[Getter] #[Setter]
        private string $name,
        #[Getter] #[Setter]
        private string $email,
        #[Getter] #[Setter]
        private ?string $password,
        #[Getter] #[Setter]
        private Carbon $birthDate,
        #[Getter] #[Setter]
        private ?Roles $role,
        #[Getter] #[Setter]
        private ?ElementStatus $status,
        #[Getter] #[Setter]
        private ?Carbon $createdAt,
        #[Getter] #[Setter]
        private ?Carbon $lastActiviy
    ) {}
}
