<?php

namespace App\Application\Commands\File;

use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;
use App\Domain\Traits\AccessorTrait;
use App\Infrastructure\Persistence\Eloquent\Models\User;

class SetFileForScanCommand
{
    use AccessorTrait;

    public function __construct(
        /** @var File[] */
        #[Getter] #[Setter]
        private array $savedFiles,
    ) {
    }
}
