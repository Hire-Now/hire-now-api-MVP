<?php

namespace App\Domain\ValueObjects;

use App\Domain\Traits\AccessorTrait;
use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;

class ContractModel
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private ContractType $type,
        #[Getter] #[Setter]
        private Payment $payment,
        #[Getter] #[Setter]
        private Benefits $benefits
    ) {
    }

    public function toArray(): array
    {
        return [
            'type'     => $this->type->toArray(),
            'payment'  => $this->payment->toArray(),
            'benefits' => $this->benefits->toArray(),
        ];
    }
}
