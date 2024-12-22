<?php

namespace App\Application\Handlers\Candidate;

use App\Application\Commands\Candidate\CreateCandidateCommand;
use App\Domain\Repositories\CandidateRepositoryInterface;
use App\Domain\Entities\Candidate;

class CreateCandidateCommandHandler
{
    private CandidateRepositoryInterface $repository;

    public function __construct(CandidateRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(CreateCandidateCommand $command): Candidate
    {
        $entity = new Candidate(null, $command->getName());
        return $this->repository->save($entity);
    }
}