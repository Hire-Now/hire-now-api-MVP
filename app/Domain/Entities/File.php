<?php

namespace App\Domain\Entities;

use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;
use App\Domain\Traits\AccessorTrait;


class File
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private ?string $id,
        #[Getter] #[Setter]
        private ?string $userId,
        #[Getter] #[Setter]
        private ?string $type,
        #[Getter] #[Setter]
        private ?string $fileName,
        #[Getter] #[Setter]
        private ?string $filePath,
        #[Getter] #[Setter]
        private ?string $fileSizeKB,
        #[Getter] #[Setter]
        private ?string $visibility,
        #[Getter] #[Setter]
        private ?string $language,
        #[Getter] #[Setter]
        private ?string $uploadDate,
        #[Getter] #[Setter]
        private ?string $vtScanId,
        #[Getter] #[Setter]
        private ?string $scanResult,
        #[Getter] #[Setter]
        private ?bool $isCV,
        #[Getter] #[Setter]
        private ?bool $isMainCV
    ) {
    }

    public function toArray(): array
    {
        return [
            'id'           => $this->id,
            'user_id'      => $this->userId,
            'type'         => $this->type,
            'file_name'    => $this->fileName,
            'file_path'    => $this->filePath,
            'file_size_kb' => $this->fileSizeKB,
            'visibility'   => $this->visibility,
            'language'     => $this->language,
            'upload_date'  => $this->uploadDate,
            'vt_scan_id'   => $this->vtScanId,
            'scan_status'  => $this->scanResult,
            'is_cv'        => $this->isCV,
            'is_main_cv'   => $this->isMainCV,
        ];
    }
}
