<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\File;
use App\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Support\Collection;

interface FileRepositoryInterface
{
    public function fetchAll(?string $name, ?string $status, string $orderBy, string $orderDirection): Collection;
    public function findById(string $id): ?File;
    public function create(User $user, File $entity, string $role): File;
    public function update(string $id, File $entity): File;
    public function delete(string $id): File;
    public function getFileWithCustomizedConditions(array $queryConditions);

}
