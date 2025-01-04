<?php

namespace App\Domain\Contracts;

use App\Domain\Entities\Consumer;
use App\Domain\Entities\User;

interface JWTServiceInterface
{
    public function generateToken(User|Consumer $entity): string;
    public function validateToken(string $token): array;
}
