<?php

namespace App\Application\UseCases;

use App\Domain\Contracts\JWTServiceInterface;
use App\Domain\Entities\Role;
use App\Domain\Entities\User;
use App\Domain\Contracts\PasswordHasherInterface;
use App\Domain\Contracts\TokenGeneratorInterface;
use App\Domain\Repositories\UserRepositoryInterface;

class UserUseCase
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private PasswordHasherInterface $passwordHasher,
        private JWTServiceInterface $jwtService
    ) {
    }

    public function createUser(User $entity): User
    {
        try {
            $hashedPassword = $this->passwordHasher->hash($entity->getPassword());
            $entity->setPassword($hashedPassword);

            return $this->repository->create($entity);
        } catch (\Throwable $e) {
            throw new \RuntimeException('Failed to create user.', 0, $e);
        }
    }

    public function setRoleToUser(string $userId, array $role): void
    {
        try {
            $this->repository->setRoleToUser($userId, $role);
        } catch (\Throwable $e) {
            throw new \RuntimeException('Failed to set role to user.', 0, $e);
        }
    }

    public function findUserById(User $entity): User
    {
        try {
            return $this->repository->findById($entity->getId());
        } catch (\Throwable $e) {
            throw new \RuntimeException('Failed to find user by ID.', 0, $e);
        }
    }

    public function updateUser(User $entity): User
    {
        try {
            return $this->repository->update($entity->getId(), $entity);
        } catch (\Throwable $e) {
            throw new \RuntimeException('Failed to update user.', 0, $e);
        }
    }

    public function validateCredentials(string $email, string $password): User
    {
        try {
            $user = $this->repository->findByEmail($email);

            if (!$this->passwordHasher->verify($password, $user->getPassword())) {
                throw new \DomainException('Invalid credentials.');
            }

            return $user;
        } catch (\DomainException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new \RuntimeException('An unexpected error occurred during authentication.', 0, $e);
        }
    }

    public function generateJWT(User $user): string
    {
        try {
            return $this->jwtService->generateToken($user);
        } catch (\Throwable $th) {
            throw new \RuntimeException('An unexpected error occurred during authentication.', 0, $th);
        }
    }
}
