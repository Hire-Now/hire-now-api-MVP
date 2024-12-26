<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Entities\User;
use App\Infrastructure\Persistence\Eloquent\Models\User as UserModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;


class UserRepository implements UserRepositoryInterface
{
    public function create(User $user): User
    {
        try {
            $userModel = UserModel::create([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'status' => $user->getStatus(),
                'last_activity' => Carbon::now(),
                'birth_date' => $user->getBirthDate()
            ]);

            $user->setId($userModel->id);
            $user->setCreatedAt($userModel->created_at);

            return $user;
        } catch (\Throwable $th) {
            throw new Exception("Error saving candidate to database", 0, $th);
        }
    }

    public function findById(string $id): ?User
    {
        return null;
    }

    public function update(string $id, User $entity): User
    {
        return new User();
    }

    public function delete(string $id): bool
    {
        return true;
    }

    public function findByEmail(string $email): User
    {
        return new User();
    }

    public function fetchAll(): Collection
    {
        return new Collection();
    }

    public function paginate(int $perPage): Collection
    {
        return new Collection();
    }
}
