<?php

namespace App\Infrastructure\Controllers;

use App\Application\UseCases\CandidateUseCase;
use App\Application\DTOs\Candidate\CandidateDTO;
use Illuminate\Http\Request;

class CandidateController
{
    public function __construct(private CandidateUseCase $useCase)
    {
    }

    public function store(Request $request)
    {
        $dto = CandidateDTO::fromRequest($request->all());
        $candidate = $this->useCase->execute($dto);

        return response()->json([
            'message'   => 'Candidate created successfully!',
            'candidate' => $candidate
        ]);
    }
}
