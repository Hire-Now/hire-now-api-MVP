<?php

namespace App\Application\UseCases;

use App\Application\Commands\Candidate\UpdateCandidateCommand;
use App\Application\DTOs\Candidate\CandidateDTO;
use App\Domain\Entities\Candidate;
use App\Domain\Repositories\CandidateRepositoryInterface;
use App\Domain\Services\CandidateService;

class CandidateUseCase
{
    // public function __construct(
    //     private CandidateRepositoryInterface $repository,
    //     private CandidateService $service
    // ) {}

    public function createCandidate(Candidate $candidate): array
    {
        //todo: validar que fecha de fin de estudio y trrbajo sea superior a la de inicio, descartar campos vacios o nullos de la entidad
        return $candidate->toArray();
    }

    // public function update(UpdateCandidateCommand $command): Candidate
    // {
    //     if (!$this->service->validateEmail($command->getEmail())) {
    //         throw new \Exception("Invalid email");
    //     }

    //     if (!$this->service->validateSkills($command->getSkills())) {
    //         throw new \Exception("Invalid skills");
    //     }

    //     $candidate = $this->repository->find($command->getId());

    //     if (!$candidate) {
    //         throw new \Exception("Candidate not found");
    //     }

    //     $candidate->setName($command->getName());
    //     $candidate->setEmail($command->getEmail());
    //     $candidate->setSkills($command->getSkills());

    //     return $this->repository->save($candidate);
    // }
}
