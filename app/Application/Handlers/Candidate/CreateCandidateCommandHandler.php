<?php

namespace App\Application\Handlers\Candidate;

use App\Application\Commands\Candidate\CreateCandidateCommand;
use App\Application\DTOs\Candidate\CandidateDTO;
use App\Application\UseCases\CandidateUseCase;
use App\Domain\Repositories\CandidateRepositoryInterface;
use App\Domain\Entities\Candidate;

class CreateCandidateCommandHandler
{
    public function __construct(private CandidateUseCase $useCase)
    {
    }

    public function handle(CreateCandidateCommand $command)
    {
        $dto = new CandidateDTO(
            name: $command->getName(),
            email: $command->getEmail(),
            skills: $command->getSkills()
        );

        return $this->useCase->execute($dto);
    }
}
