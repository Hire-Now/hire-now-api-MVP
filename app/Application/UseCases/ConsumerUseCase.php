<?php

namespace App\Application\UseCases;

use App\Application\Contracts\ConsumerAuthInterface;
use App\Domain\Ports\Outbound\JWTServicePort;

class ConsumerUseCase
{
    public function __construct(private JWTServicePort $jwtService, private ConsumerAuthInterface $consumerAuthService)
    {
    }

    public function authenticate(string $authorization): ?string
    {
        try {
            if (!str_starts_with($authorization, 'Basic ')) {
                return null;
            }

            $consumer = $this->consumerAuthService->authenticate($authorization);

            return $this->jwtService->generateToken($consumer);
        } catch (\Throwable $e) {
            throw new \RuntimeException('Failed to authenticate the consumer.', 0, $e);
        }
    }

}
