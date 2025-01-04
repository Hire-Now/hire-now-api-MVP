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
use App\Infrastructure\Requests\CreateCandidateRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CandidateController
{
    public function __construct(private CandidateUseCase $candidateUseCase, private CreateCandidateCommandHandler $createCandidateCommandHandler)
    {
    }

    //todo: estandarizar response
    public function store(CreateCandidateRequest $request)
    {
        try {
            $request = $request->validated();

            $command = new CreateCandidateCommand();

            $candidate = $this->createCandidateCommandHandler->handle($command);

            return response()->json([
                'message'   => 'Candidate created successfully!',
                'candidate' => $candidate
            ]);
        } catch (BadRequestException $th) {
            return response()->json([
                'message'   => $th->getMessage(),
                'candidate' => []
            ], status: 400);
        } catch (\Throwable $th) {
            return response()->json([
                'message'   => 'An unexpected error just happened!',
                'candidate' => []
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
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

            $candidate = $handler->handle($command);

            return response()->json([
                'message'   => 'Candidate updated successfully!',
                'candidate' => $candidate
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message'   => 'An unexpected error just happened!',
                'candidate' => []
            ], 500);
        }
    }
}
