<?php

namespace App\Infrastructure\Persistence\Eloquent\Adapters;

use App\Domain\Entities\File;
use App\Infrastructure\Persistence\Eloquent\Models\File as FileModel;

class FileAdapter
{
    public static function toDomain(FileModel $eloquentFile): File
    {
        return new File(
            $eloquentFile->id,
            $eloquentFile->owner_id,
            $eloquentFile->type,
            $eloquentFile->name,
            $eloquentFile->path,
            $eloquentFile->size,
            $eloquentFile->metadata['visibility'],
            $eloquentFile->language,
            $eloquentFile->created_at,
            $eloquentFile->vt_scan_id,
            $eloquentFile->scan_status,
            $eloquentFile->metadata['is_cv'],
            $eloquentFile->metadata['is_main_cv']
        );
    }
}
