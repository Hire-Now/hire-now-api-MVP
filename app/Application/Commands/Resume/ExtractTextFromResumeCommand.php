<?php

namespace App\Application\Commands\Resume;

use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;
use App\Domain\Traits\AccessorTrait;
use App\Infrastructure\Persistence\Eloquent\Models\User;

class ExtractTextFromResumeCommand
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private string $userId,
        #[Getter] #[Setter]
        private string $role,
    ) {
    }
}
