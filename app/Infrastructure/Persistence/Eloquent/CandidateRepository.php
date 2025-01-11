<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Repositories\CandidateRepositoryInterface;
use App\Domain\Entities\Candidate;
use App\Infrastructure\Persistence\Eloquent\Models\Candidate as CandidateModel;
use Exception;

class CandidateRepository //implements CandidateRepositoryInterface
{
    public function save(Candidate $candidate): Candidate
    {
        try {
            if ($candidate->getId()) {
                $candidateModel = CandidateModel::find($candidate->getId());
                $candidateModel->name = $candidate->getName();
                $candidateModel->email = $candidate->getEmail();
                $candidateModel->skills = $candidate->getSkills();
                $candidateModel->save();

                return $candidate;
            }

            $candidateModel = CandidateModel::create([
                'name'   => $candidate->getName(),
                'email'  => $candidate->getEmail(),
                'skills' => $candidate->getSkills()
            ]);

            return $candidate;
        } catch (\Throwable $th) {
            throw new Exception("Error saving candidate to database", 0, $th);
        }
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
