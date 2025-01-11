<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Candidate;
use Illuminate\Support\Collection;

interface ICandidateRepository
{
    public function create(Candidate $entity): Candidate;
    // public function findById(string $id): ?Candidate;
    // public function update(string $id, Candidate $entity): Candidate;
    // public function delete(string $id): bool;
    // public function findByEmail(string $email): Candidate;
    // public function fetchAll(): Collection;
    // public function paginate(int $perPage): Collection;
}
