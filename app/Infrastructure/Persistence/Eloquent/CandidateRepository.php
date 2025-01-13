<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Entities\Candidate;
use App\Domain\Ports\Outbound\CandidateRepositoryPort;
use App\Infrastructure\Persistence\Eloquent\Models\Candidate as CandidateModel;
use App\Infrastructure\Utils\ArrayHelper;
use Exception;

class CandidateRepository implements CandidateRepositoryPort
{
    public function create(Candidate $candidate): Candidate
    {
        try {
            $candidateArray = ArrayHelper::removeEmptyOrNullElements($candidate->toArray());

            // $candidateModel = CandidateModel::create(
            //     $candidateArray
            // );

            return $candidate;
        } catch (\Throwable $th) {
            throw new Exception("Error saving candidate to database", 0, $th);
        }
    }

    // public function find(string $id): ?Candidate
    // {
    //     $candidateModel = CandidateModel::find($id);

    //     if (!$candidateModel) {
    //         return null;
    //     }

    //     return new Candidate(
    //         id: $candidateModel->id,
    //         name: $candidateModel->name,
    //         email: $candidateModel->email,
    //         skills: $candidateModel->skills
    //     );
    // }
}
