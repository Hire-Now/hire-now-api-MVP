<?php

namespace App\Domain\Services;

use App\Domain\Entities\Candidate;
use App\Domain\Repositories\CandidateRepositoryInterface;

class CandidateService{
    public function __construct(private CandidateRepositoryInterface $repository)
    {}

    public function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function validateSkills(string $skills): bool
    {
        return strlen($skills) > 5;
    }
}
