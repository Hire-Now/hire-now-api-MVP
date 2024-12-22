<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Repositories\CandidateRepositoryInterface;
use App\Domain\Entities\Candidate;
use App\Infrastructure\Persistence\Eloquent\Models\Candidate as CandidateModel;

class CandidateRepository implements CandidateRepositoryInterface
{
    public function save(Candidate $candidate): Candidate
    {
        if ($candidate->id) {
            $candidateModel = CandidateModel::find($candidate->id);
            $candidateModel->name = $candidate->name;
            $candidateModel->email = $candidate->email;
            $candidateModel->skills = $candidate->skills;
            $candidateModel->save();

            return $candidate;
        }

        return CandidateModel::create([
            'name'   => $candidate->name,
            'email'  => $candidate->email,
            'skills' => $candidate->skills
        ]);
    }

    public function find(string $id): ?Candidate
    {
        $candidateModel = CandidateModel::find($id);

        if (!$candidateModel) {
            return null;
        }

        return new Candidate(
            id: $candidateModel->id,
            name: $candidateModel->name,
            email: $candidateModel->email,
            skills: $candidateModel->skills
        );
    }
}
