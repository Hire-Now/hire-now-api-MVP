<?php

namespace App\Infrastructure\Persistence\Eloquent\Adapters;

use App\Domain\Entities\Consumer;
use App\Infrastructure\Persistence\Eloquent\Models\ApiConsumer as ConsumerModel;

class ConsumerAdapter
{
    public static function toDomain(ConsumerModel $eloquentConsumer): Consumer
    {
        return new Consumer(
            $eloquentConsumer->id,
            $eloquentConsumer->name,
            $eloquentConsumer->client_id,
            $eloquentConsumer->client_secret,
            $eloquentConsumer->description,
            $eloquentConsumer->is_active,
            $eloquentConsumer->last_access_at
            ? new \DateTimeImmutable($eloquentConsumer->last_access_at)
            : null
        );
    }

    public static function toEloquent(Consumer $domainConsumer): ConsumerModel
    {
        $eloquentConsumer = new ConsumerModel();
        $eloquentConsumer->id = $domainConsumer->getId();
        $eloquentConsumer->name = $domainConsumer->getName();
        $eloquentConsumer->client_id = $domainConsumer->getClientId();
        $eloquentConsumer->client_secret = $domainConsumer->getClientSecret();
        $eloquentConsumer->description = $domainConsumer->getDescription();
        $eloquentConsumer->is_active = $domainConsumer->getIsActive();
        $eloquentConsumer->last_access_at = $domainConsumer->getLastAccessAt()
            ? $domainConsumer->getLastAccessAt()->format('Y-m-d H:i:s')
            : null;

        return $eloquentConsumer;
    }
}
