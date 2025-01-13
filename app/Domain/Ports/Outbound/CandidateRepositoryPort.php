<?php

namespace App\Domain\Ports\Outbound;

use App\Domain\Entities\Candidate;
use Illuminate\Support\Collection;

interface CandidateRepositoryPort
{
    public function create(Candidate $entity): Candidate;
}
