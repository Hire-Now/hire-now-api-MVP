<?php

namespace App\Application\Handlers\Consumer;

use App\Application\Commands\Consumer\AuthenticateConsumerCommand;
use App\Application\UseCases\ConsumerUseCase;

class AuthenticateConsumerCommandHandler
{
    public function __construct(private ConsumerUseCase $useCase)
    {
    }

    public function handle(AuthenticateConsumerCommand $command): ?string
    {
        return $this->useCase->authenticate($command->getBasicAuthorization());
    }
}
