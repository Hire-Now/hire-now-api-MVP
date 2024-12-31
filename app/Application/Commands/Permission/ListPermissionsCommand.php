<?php

namespace App\Application\Commands\Permission;

use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;
use App\Domain\Traits\AccessorTrait;

class ListPermissionsCommand
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private ?string $name,
        #[Getter] #[Setter]
        private ?string $status,
        #[Getter] #[Setter]
        private string $orderBy,
        #[Getter] #[Setter]
        private string $orderDirection
    ) {
    }
}
