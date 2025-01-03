<?php

namespace App\Infrastructure\Repositories;

use App\Application\Contracts\ConsumerRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Adapters\ConsumerAdapter;
use App\Infrastructure\Persistence\Eloquent\Models\ApiConsumer;
use App\Domain\Entities\Consumer;

class ConsumerRepository implements ConsumerRepositoryInterface
{
    public function findByClientId(string $clientId): ?Consumer
    {
        try {
            $consumerModel = ApiConsumer::where('client_id', $clientId)->first();
            return ConsumerAdapter::toDomain($consumerModel);
        } catch (\Throwable $th) {
            throw new \Exception("Error processing data.", 0, $th);
        }
    }

    public function updateLastAccess(Consumer $consumer): void
    {
        $consumer = ConsumerAdapter::toEloquent($consumer);

        $consumer->update([ 'last_access_at' => now() ]);
    }
}

