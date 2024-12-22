<?php

namespace App\Application\Handlers\Candidate;

use App\Application\Commands\Candidate\UpdateCandidateCommand;
use App\Application\UseCases\CandidateUseCase;
use App\Domain\Repositories\CandidateRepositoryInterface;

class UpdateCandidateCommandHandler
{
    public function __construct(private CandidateUseCase $useCase)
    {
    }

    public function handle(UpdateCandidateCommand $command)
    {
        return $this->useCase->update($command);
    }
}
