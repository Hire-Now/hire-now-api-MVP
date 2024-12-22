<?php

namespace App\Infrastructure\Controllers;

use App\Application\Commands\Candidate\CreateCandidateCommand;
use App\Application\Commands\Candidate\UpdateCandidateCommand;
use App\Application\UseCases\CandidateUseCase;
use App\Application\DTOs\Candidate\CandidateDTO;
use App\Application\Handlers\Candidate\CreateCandidateCommandHandler;
use App\Application\Handlers\Candidate\UpdateCandidateCommandHandler;
use App\Domain\Services\CandidateService;
use App\Infrastructure\Persistence\Eloquent\CandidateRepository;
use Illuminate\Http\Request;

class CandidateController
{
    public function __construct(private CandidateUseCase $candidateUseCase)
    {
    }

    //todo: crear elemento de tipo request y estandarizar response
    public function store(Request $request)
    {
        $dto = CandidateDTO::fromRequest($request->all());

        $command = new CreateCandidateCommand(
            $dto->name,
            $dto->email,
            $dto->skills
        );

        $handler = new CreateCandidateCommandHandler(new CandidateUseCase(
            new CandidateRepository(),
            new CandidateService()
        ));

        $candidate = $handler->handle($command);

        return response()->json([
            'message'   => 'Candidate created successfully!',
            'candidate' => $candidate
        ]);
    }

    public function update(Request $request, $id)
    {
        $command = new UpdateCandidateCommand(
            $id,
            $request->name,
            $request->email,
            $request->skills
        );

        $handler = new UpdateCandidateCommandHandler(new CandidateUseCase(
            new CandidateRepository(),
            new CandidateService()
        ));

        return $handler->handle($command);
    }
}
