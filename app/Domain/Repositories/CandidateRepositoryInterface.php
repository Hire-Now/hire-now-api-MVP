<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Candidate;

interface CandidateRepositoryInterface
{
    public function save(Candidate $entity): Candidate;
}