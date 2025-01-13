<?php

namespace App\Domain\Ports\Outbound;

use App\Domain\Entities\Consumer;
use App\Domain\Entities\User;

interface JWTServicePort
{
    public function generateToken(User|Consumer $entity): string;
    public function validateToken(string $token): array;
}
