<?php

namespace App\Application\Queries\Resume;

use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;
use App\Domain\Traits\AccessorTrait;
use App\Infrastructure\Persistence\Eloquent\Models\User;

class GetMainResumeQuery
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private string $userId,
        #[Getter] #[Setter]
        private string $role,
        #[Getter] #[Setter]
        private bool $isCV,
        #[Getter] #[Setter]
        private bool $isMainCV,
    ) {
    }
}
