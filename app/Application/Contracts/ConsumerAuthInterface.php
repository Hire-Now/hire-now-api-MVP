<?php

namespace App\Application\Contracts;

use App\Domain\Entities\Consumer;

interface ConsumerAuthInterface
{
    public function authenticate(string $authorizationHeader): ?Consumer;
}
