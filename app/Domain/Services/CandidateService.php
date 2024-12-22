<?php

namespace App\Domain\Services;

use App\Domain\Entities\Candidate;
use App\Domain\Repositories\CandidateRepositoryInterface;

class CandidateService{
    public function __construct(private CandidateRepositoryInterface $repository) {}
}