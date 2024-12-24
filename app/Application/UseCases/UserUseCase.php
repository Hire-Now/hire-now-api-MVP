<?php

namespace App\Application\UseCases;

use App\Application\DTOs\User\UserDTO;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Entities\User;

class UserUseCase
{
    public function __construct(private UserRepositoryInterface $repository) {}

    public function execute(UserDTO $dto): User
    {
        $entity = new User(
            id: null,
            name: $dto->name
        );

        return $this->repository->save($entity);
    }
}
