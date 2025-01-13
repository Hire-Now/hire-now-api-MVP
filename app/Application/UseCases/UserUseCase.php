<?php

namespace App\Application\UseCases;

use App\Domain\Entities\User;
use App\Domain\Ports\Outbound\JWTServicePort;
use App\Domain\Ports\Outbound\PasswordHasherPort;
use App\Domain\Ports\Outbound\UserRepositoryPort;
use Illuminate\Database\Eloquent\Collection;

class UserUseCase
{
    public function __construct(
        private UserRepositoryPort $repository,
        private PasswordHasherPort $passwordHasher,
        private JWTServicePort $jwtService
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

    public function setRolesToUser(string $userId, array $roles): User
    {
        try {
            return $this->repository->setRolesToUser($userId, $roles);
        } catch (\Throwable $e) {
            throw new \RuntimeException('Failed to set role to user.', 0, $e);
        }
    }

    public function removeRolesToUser(string $userId, array $roles): User
    {
        try {
            return $this->repository->removeRolesToUser($userId, $roles);
        } catch (\Throwable $e) {
            throw new \RuntimeException('Failed to remove role to user.', 0, $e);
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
            if (!$entity->getUserActivation()) {
                $hashedPassword = $this->passwordHasher->hash($entity->getPassword());
                $entity->setPassword($hashedPassword);
            }

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

    public function fetchUsers(?string $name, ?string $status, string $orderBy, string $orderDirection): Collection
    {
        try {
            return $this->repository->fetchAll($name, $status, $orderBy, $orderDirection);
        } catch (\Throwable $th) {
            throw new \RuntimeException('An unexpected error occurred during authentication.', 0, $th);
        }
    }

    public function deleteUser(string $id): bool
    {
        try {
            return $this->repository->delete($id);
        } catch (\Throwable $th) {
            throw new \RuntimeException('An unexpected error occurred during authentication.', 0, $th);
        }
    }
}
