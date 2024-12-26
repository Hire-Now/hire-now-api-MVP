<?php

namespace App\Domain\Services;

use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepositoryInterface;

class UserService
{
    public function __construct(private UserRepositoryInterface $repository)
    {}

    public function validateSkills(string $skills): bool
    {
        return strlen($skills) > 5;
    }
}
