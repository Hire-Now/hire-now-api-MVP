<?php

namespace App\Domain\Contracts;

use App\Domain\Entities\User;

interface JWTServiceInterface
{
    public function generateToken(User $user): string;
    public function validateToken(string $token): array;
}
