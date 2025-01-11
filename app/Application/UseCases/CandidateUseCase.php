<?php

namespace App\Application\UseCases;

use App\Application\Commands\Candidate\UpdateCandidateCommand;
use App\Application\DTOs\Candidate\CandidateDTO;
use App\Domain\Entities\Candidate;
use App\Domain\Repositories\CandidateRepositoryInterface;
use App\Domain\Services\CandidateService;
use Carbon\Carbon;

class CandidateUseCase
{
    // public function __construct(
    //     private CandidateRepositoryInterface $repository,
    //     private CandidateService $service
    // ) {}

    public function createCandidate(Candidate $candidate): Candidate
    {
        try {
            //Refactor resp. unica
            $monthsOfExperience = [];

            $candidateArray = collect($candidate->toArray())->map(function ($value, $key) use (&$monthsOfExperience) {
                if (is_array($value)) {
                    foreach ($value as &$entry) {
                        if (isset($entry['start_date']) && isset($entry['end_date'])) {

                            $key = (string) $key;

                            if ($entry['end_date']->lessThan($entry['start_date'])) {
                                throw new \RuntimeException("La fecha de fin debe ser mayor que la fecha de inicio para el campo $key.");
                            }

                            if ($entry['start_date']->greaterThan($entry['end_date'])) {
                                throw new \RuntimeException("La fecha de inicio debe ser inferior que la fecha de fin para el campo $key.");
                            }

                            if ($key === 'previous_experiences') {
                                $monthsOfExperience[] = $entry['start_date']->diffInMonths($entry['end_date']);
                            }
                        }
                    }
                }
                return $value;
            })->filter(function ($value, $key): bool {
                return $key === 'years_of_experience' || (!is_null($value) && $value !== '');
            })->toArray();

            $totalMonths = collect($monthsOfExperience)->sum();
            $yearsOfExperience = intdiv($totalMonths, 12);
            $months = $totalMonths % 12;

            $candidateArray['years_of_experience'] = "$yearsOfExperience - $months";
            $candidate->setYearsOfExperience($candidateArray['years_of_experience']);

            //todo: create db record
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
