<?php

namespace App\Application\Handlers\User;

use App\Application\Commands\User\CreateUserCommand;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Entities\User;

class CreateUserCommandHandler
{
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(CreateUserCommand $command): User
    {
        $entity = new User(null, $command->getName());
        return $this->repository->save($entity);
    }
}