<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Entities\User;
use App\Domain\Services\PasswordHasherInterface;

class UserUseCase
{
    public function __construct(private UserRepositoryInterface $repository, private PasswordHasherInterface $passwordHasher)
    {}

    public function createUser(User $entity): User
    {
        $hashedPassword = $this->passwordHasher->hash($entity->getPassword());

        $entity->setPassword($hashedPassword);

        return $this->repository->create($entity);
    }
}
