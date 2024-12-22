<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\CandidateRepositoryInterface;
use App\Application\DTOs\CandidateDTO;
use App\Domain\Entities\Candidate;

class CandidateUseCase
{
    public function __construct(private CandidateRepositoryInterface $repository) {}

    public function execute(CandidateDTO $dto): Candidate
    {
        $entity = new Candidate(
            id: null,
            name: $dto->name
        );

        return $this->repository->save($entity);
    }
}