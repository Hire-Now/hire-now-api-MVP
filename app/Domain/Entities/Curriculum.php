<?php

namespace App\Domain\Entities;

use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;
use App\Domain\Traits\AccessorTrait;


class Curriculum
{
    use AccessorTrait;

    public function __construct(
        private string $id,
        private string $userId,
        private string $userType,
        private string $filePath,
        private \DateTime $uploadedAt,
        private array $skills = [],
        private array $languages = [],
        private int $yearsOfExperience = 0,
        private array $previousExperiences = [],
        private array $education = [],
        private ?array $certifications = null,
        private ?array $achievements = null,
        private ?string $professionalSummary = null,
        private ?array $portfolioLinks = null,
        private ?array $contactInfo = null,
        private ?\DateTime $lastProcessedAt = null,
        private string $status = 'uploaded',
        private ?array $processingErrors = null,
        private bool $isVerified = false,
        private ?\DateTime $createdAt = null,
        private ?\DateTime $updatedAt = null
    ) {
    }

    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'user_id'     => $this->userId,
            'user_type'   => $this->userType,
            'file_path'   => $this->filePath,
            'uploaded_at' => $this->uploadedAt,
            'skills'      => $this->skills,
            'languages'   => $this->languages,
            // 'years_or_experience' =>
        ];
    }
}
