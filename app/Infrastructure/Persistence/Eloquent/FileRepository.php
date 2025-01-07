<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Entities\File;
use App\Domain\Repositories\FileRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Support\Collection;

class FileRepository implements FileRepositoryInterface
{

    public function fetchAll(?string $name, ?string $status, string $orderBy, string $orderDirection): Collection
    {
        return new Collection();
    }

    public function findById(string $id): ?File
    {
    }

    public function create(User $user, File $entity): File
    {
        $fileRecord = $user->files()->create([
            'name'        => $entity->getFileName(),
            'path'        => $entity->getFilePath(),
            'type'        => $entity->getType(),
            'size'        => $entity->getFileSizeKB(),
            'language'    => $entity->getLanguage(),
            'vt_scan_id'  => '',
            'scan_status' => 'pending',
            'metadata'    => json_encode([
                'uploaded_by' => $entity->getUserId(),
                'visibility'  => $entity->getVisibility(),
            ]),
        ]);

        $entity->setId($fileRecord->id);
        $entity->setUploadDate($fileRecord->created_at);
        $entity->setScanResult($fileRecord->scan_status);

        return $entity;
    }

    public function update(string $id, File $entity): File
    {
    }

    public function delete(string $id): File
    {
    }
}
