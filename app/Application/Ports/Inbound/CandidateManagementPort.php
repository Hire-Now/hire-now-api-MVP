<?php

namespace App\Application\Ports\Inbound;

use App\Domain\Entities\Candidate;

interface CandidateManagementPort
{
    public function createCandidate(array $candidateData): Candidate;
    // public function updateCandidate(int $candidateId, array $candidateData);
    // public function deleteCandidate(int $candidateId);
    // public function getCandidateById(int $candidateId);
}

