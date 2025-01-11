<?php

namespace App\Application\UseCases;

use App\Application\Contracts\ICandidateUseCase;
use App\Domain\Entities\Candidate;
use App\Domain\Repositories\ICandidateRepository;
use App\Domain\Services\CandidateService;
use App\Infrastructure\Utils\ArrayHelper;

class CandidateUseCase implements ICandidateUseCase
{
    public function __construct(private readonly ICandidateRepository $repository, private readonly CandidateService $service)
    {
    }

    public function createCandidate(Candidate $candidate): Candidate
    {
        try {
            $candidateArray = $this->service->validateDatesAndExtractExperience($candidate->toArray());

            [ $candidateArray['years_of_experience'], $candidateArray['months_of_experience'] ] = $this->service->calculateYearsOfExperienceWithMonths($candidateArray['months_of_experience']);

            $candidate->setYearsOfExperience($candidateArray['years_of_experience']);
            $candidate->setMonthsOfExperience($candidateArray['months_of_experience']);

            $this->repository->create($candidate);

            return $candidate;
        } catch (\Throwable $e) {
            throw new \RuntimeException('Failed to create candidate.', 0, $e);
        }
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
