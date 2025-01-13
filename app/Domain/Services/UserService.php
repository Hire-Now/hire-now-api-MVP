<?php

namespace App\Domain\Services;

use App\Domain\Ports\Outbound\UserRepositoryPort;

class UserService
{
    public function __construct(private UserRepositoryPort $repository)
    {
    }

    public function validateSkills(string $skills): bool
    {
        return strlen($skills) > 5;
    }
}
