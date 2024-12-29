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
        private ?string $name,
        #[Getter] #[Setter]
        private ?string $email,
        #[Getter] #[Setter]
        private ?string $password,
        #[Getter] #[Setter]
        private ?Carbon $birthDate,
        /** @var Role[] */
        #[Getter] #[Setter]
        private ?array $roles,
        #[Getter] #[Setter]
        private ?ElementStatus $status,
        #[Getter] #[Setter]
        private ?Carbon $createdAt,
        #[Getter] #[Setter]
        private ?Carbon $lastActivity
    ) {
    }

    public function addRole(string $role)
    {
        $this->roles[] = $role;
    }

    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'email'       => $this->email,
            'birthDate'   => $this->birthDate?->format('Y-m-d H:i:s'),
            'roles'       => $this->roles,
            'status'      => $this->status?->value,
            'createdAt'   => $this->createdAt?->format('Y-m-d H:i:s'),
            'lastActiviy' => $this->lastActivity?->format('Y-m-d H:i:s'),
        ];
    }
}
