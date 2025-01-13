<?php

namespace App\Application\Contracts;

use App\Domain\Entities\Consumer;

interface ConsumerRepositoryPort
{
    public function findByClientId(string $clientId): ?Consumer;
    public function updateLastAccess(Consumer $consumer): void;
    public function findById(string $consumerId): ?Consumer;
}
