<?php

namespace App\Application\Services;

use App\Application\Contracts\ConsumerAuthInterface;
use App\Application\Contracts\ConsumerRepositoryInterface;
use App\Domain\Entities\Consumer;
use App\Domain\Models\ApiConsumer;

class ConsumerAuthService implements ConsumerAuthInterface
{
    private ConsumerRepositoryInterface $consumerRepository;

    public function __construct(ConsumerRepositoryInterface $consumerRepository)
    {
        $this->consumerRepository = $consumerRepository;
    }

    public function authenticate(string $authorizationHeader): ?Consumer
    {
        try {
            $decoded = base64_decode(substr($authorizationHeader, 6));
            [ $clientId, $clientSecret ] = explode(':', $decoded, 2);

            $consumer = $this->consumerRepository->findByClientId($clientId);

            if (!$consumer || !password_verify($clientSecret, $consumer->getClientSecret()) || !$consumer->getIsActive()) {
                return null;
            }

            $this->consumerRepository->updateLastAccess($consumer);

            return $consumer;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage(), 0, $th);
        }
    }
}
