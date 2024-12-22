<?php

namespace App\Application\UseCases;

use App\Application\Commands\Candidate\UpdateCandidateCommand;
use App\Application\DTOs\Candidate\CandidateDTO;
use App\Domain\Entities\Candidate;
use App\Domain\Repositories\CandidateRepositoryInterface;
use App\Domain\Services\CandidateService;

class CandidateUseCase
{
    public function __construct(
        private CandidateRepositoryInterface $repository,
        private CandidateService $service
    ) {}

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

    public function update(UpdateCandidateCommand $command): Candidate
    {
        // Validaciones
        if (!$this->service->validateEmail($command->getEmail())) {
            throw new \Exception("Invalid email");
        }

        if (!$this->service->validateSkills($command->getSkills())) {
            throw new \Exception("Invalid skills");
        }

        // Buscar el candidato a actualizar
        $candidate = $this->repository->find($command->getId());

        if (!$candidate) {
            throw new \Exception("Candidate not found");
        }

        $candidate->name = $command->getName();
        $candidate->email = $command->getEmail();
        $candidate->skills = $command->getSkills();

        return $this->repository->save($candidate);
    }
}
