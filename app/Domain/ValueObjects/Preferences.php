<?php

namespace App\Domain\ValueObjects;

use App\Domain\Traits\AccessorTrait;
use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;

class Preferences
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private WorkModel $workModel,
        #[Getter] #[Setter]
        private ContractModel $contractModel
    ) {
    }
}
