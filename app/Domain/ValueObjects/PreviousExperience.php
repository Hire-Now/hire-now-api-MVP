<?php

namespace App\Domain\ValueObjects;

use App\Domain\Traits\AccessorTrait;
use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;
use Carbon\Carbon;

class PreviousExperience
{
    use AccessorTrait;

    public function __construct(
        #[Setter] #[Getter]
        private string $companyName,
        #[Setter] #[Getter]
        private string $role,
        #[Setter] #[Getter]
        private Carbon $startDate,
        #[Setter] #[Getter]
        private ?Carbon $endDate,
        #[Setter] #[Getter]
        private string $description,
        #[Setter] #[Getter]
        private array $technologies,
        /** @var Milestone[]*/
        #[Setter] #[Getter]
        private array $milestones,
    ) {
    }

    public function toArray(): array
    {
        return [
            'company_name' => $this->companyName,
            'role'         => $this->role,
            'start_date'   => $this->startDate,
            'end_date'     => $this->endDate,
            'description'  => $this->description,
            'technologies' => $this->technologies,
            'milestones'   => array_map(fn(Milestone $milestone) => $milestone->toArray(), $this->milestone ?? []),
        ];
    }
}
