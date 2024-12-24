<?php

namespace App\Domain\Services;

use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepositoryInterface;

class UserService{
    public function __construct(private UserRepositoryInterface $repository) {}
}