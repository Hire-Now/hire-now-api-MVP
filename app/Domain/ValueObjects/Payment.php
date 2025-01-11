<?php

namespace App\Domain\ValueObjects;

use App\Domain\Traits\AccessorTrait;
use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;

class Payment
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private string $currency,
        #[Getter] #[Setter]
        private Rate $hourlyRate,
        #[Getter] #[Setter]
        private Rate $monthlyFixed,
        #[Getter] #[Setter]
        private bool $projectFixed
    ) {
    }

    public function toArray(): array
    {
        return [
            'currency'      => $this->currency,
            'hourly_rate'   => $this->hourlyRate,
            'monthly_fixed' => $this->monthlyFixed,
            'project_fixed' => $this->projectFixed,
        ];
    }
}
