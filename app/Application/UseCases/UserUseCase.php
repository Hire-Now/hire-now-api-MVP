<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Entities\User;
use App\Domain\Contracts\PasswordHasherInterface;
use App\Domain\Contracts\TokenGeneratorInterface;

class UserUseCase
{
    public function __construct(private UserRepositoryInterface $repository, private ?PasswordHasherInterface $passwordHasher)
    {
    }

    public function createUser(User $entity): User
    {
        $hashedPassword = $this->passwordHasher->hash($entity->getPassword());

        $entity->setPassword($hashedPassword);

        return $this->repository->create($entity);
    }

    public function findUserById(User $entity): User
    {
        return $this->repository->findById($entity->getId());
    }

    public function updateUser(User $entity): User
    {
        return $this->repository->update($entity->getId(), $entity);
    }
}
