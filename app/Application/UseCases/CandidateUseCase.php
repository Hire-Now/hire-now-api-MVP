<?php

namespace App\Application\UseCases;

use App\Application\DTOs\Candidate\CandidateDTO;
use App\Domain\Entities\Candidate;
use App\Domain\Repositories\CandidateRepositoryInterface;
use App\Domain\Services\CandidateService;

class CandidateUseCase
{
    public function __construct(
        private CandidateRepositoryInterface $repository,
        private CandidateService $service
    ) {
    }

    public function execute(CandidateDTO $dto): Candidate
    {
        // Validaciones
        if (!$this->service->validateEmail($dto->email)) {
            throw new \Exception("Invalid email");
        }

        if (!$this->service->validateSkills($dto->skills)) {
            throw new \Exception("Invalid skills");
        }

        // Crear candidato y guardarlo
        $candidate = new Candidate(
            id: null,
            name: $dto->name,
            email: $dto->email,
            skills: $dto->skills
        );

        return $this->repository->save($candidate);
    }
}
