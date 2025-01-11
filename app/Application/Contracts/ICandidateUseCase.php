<?php

namespace App\Application\Contracts;

use App\Domain\Entities\Candidate;

interface ICandidateUseCase
{
    public function createCandidate(Candidate $candidate): Candidate;
}
