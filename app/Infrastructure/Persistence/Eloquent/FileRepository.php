<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Entities\File;
use App\Domain\Repositories\FileRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Adapters\FileAdapter;
use App\Infrastructure\Persistence\Eloquent\Models\File as ModelsFile;
use App\Infrastructure\Persistence\Eloquent\Models\User;
use Exception;
use Illuminate\Support\Collection;

class FileRepository implements FileRepositoryInterface
{
    public function create(User $user, File $entity, string $role): File
    {
        try {
            $hasMainCV = ModelsFile::where('metadata->is_main_cv', true)
                ->where('owner_id', $user->id)
                ->exists();

            $isMainCV = $entity->getIsMainCV() && !$hasMainCV;

            $fileRecord = $user->files()->create([
                'name'        => $entity->getFileName(),
                'path'        => $entity->getFilePath(),
                'type'        => $entity->getType(),
                'size'        => $entity->getFileSizeKB(),
                'language'    => $entity->getLanguage(),
                'vt_scan_id'  => '',
                'scan_status' => 'pending',
                'metadata'    => json_encode(
                    [
                        'uploaded_by' => $role,
                        'visibility'  => $entity->getVisibility(),
                        'is_cv'       => $entity->getIsCV(),
                        'is_main_cv'  => $isMainCV,
                    ]
                ),
            ]);

            $entity->setId($fileRecord->id);
            $entity->setUploadDate($fileRecord->created_at);
            $entity->setScanResult($fileRecord->scan_status);

            return $entity;
        } catch (\Throwable $th) {
            throw new Exception("Error saving file record on the database", 0, $th);
        }
    }

    public function getFileWithCustomizedConditions(array $queryConditions): File
    {
        try {
            $record = ModelsFile::where($queryConditions)->firstOrFail();

            return FileAdapter::toDomain($record);
        } catch (\Throwable $th) {
            throw new Exception("Error saving file record on the database", 0, $th);
        }
    }
}
