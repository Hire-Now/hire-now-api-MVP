<?php

namespace App\Domain\Ports\Outbound;

use App\Domain\Entities\File;
use App\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Support\Collection;

interface FileRepositoryPort
{
    public function create(User $user, File $entity, string $role): File;
    public function getFileWithCustomizedConditions(array $queryConditions): File;
}
