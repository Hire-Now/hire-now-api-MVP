<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Application\Contracts\ConsumerRepositoryPort;
use App\Infrastructure\Persistence\Eloquent\Adapters\ConsumerAdapter;
use App\Infrastructure\Persistence\Eloquent\Models\ApiConsumer;
use App\Domain\Entities\Consumer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ConsumerRepository implements ConsumerRepositoryPort
{
    public function findByClientId(string $clientId): ?Consumer
    {
        try {
            $consumerModel = ApiConsumer::where('client_id', $clientId)->first();

            if (!$consumerModel) {
                throw new ModelNotFoundException('Record was not found with provided id.');
            }

            return ConsumerAdapter::toDomain($consumerModel);
        } catch (ModelNotFoundException $th) {
            throw $th;
        } catch (\Throwable $th) {
            throw new \Exception("Error while looking for a consumer on the DB.", 0, $th);
        }
    }

    public function findById(string $consumerId): ?Consumer
    {
        try {
            $consumerModel = ApiConsumer::where('id', $consumerId)->first();

            if (!$consumerModel) {
                throw new ModelNotFoundException('Record was not found with provided id.');
            }

            return ConsumerAdapter::toDomain($consumerModel);
        } catch (ModelNotFoundException $th) {
            throw $th;
        } catch (\Throwable $th) {
            throw new \Exception("Error while looking for a consumer on the DB.", 0, $th);
        }
    }

    public function updateLastAccess(Consumer $consumer): void
    {
        $consumer = ConsumerAdapter::toEloquent($consumer);

        $consumer->update([ 'last_access_at' => now() ]);
    }
}

