<?php

namespace App\Domain\ValueObjects;

use App\Domain\Traits\AccessorTrait;
use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;

class Benefits
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private bool $healthInsurance,
        #[Getter] #[Setter]
        private bool $paidTimeOff,
        #[Getter] #[Setter]
        private bool $retirementPlan
    ) {
    }

    public function toArray(): array
    {
        return [
            'health_insurance' => $this->healthInsurance,
            'paid_time_off'    => $this->paidTimeOff,
            'retirement_plan'  => $this->retirementPlan,
        ];
    }
}
