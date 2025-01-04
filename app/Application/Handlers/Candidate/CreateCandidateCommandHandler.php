<?php

namespace App\Application\Handlers\Candidate;

use App\Application\Commands\Candidate\CreateCandidateCommand;
use App\Application\UseCases\CandidateUseCase;
use App\Domain\Entities\Candidate;

class CreateCandidateCommandHandler
{
    public function __construct(private CandidateUseCase $useCase)
    {
    }

    public function handle(CreateCandidateCommand $command)
    {
        return $this->useCase->execute(new Candidate(
            $command
        ));
    }
}
