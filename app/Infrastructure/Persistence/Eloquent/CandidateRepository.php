<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Repositories\CandidateRepositoryInterface;
use App\Domain\Entities\Candidate;
use App\Infrastructure\Persistence\Eloquent\Models\Candidate as CandidateModel;

class CandidateRepository implements CandidateRepositoryInterface
{
    public function save(Candidate $candidate): Candidate
    {
        $model = CandidateModel::create([
            'name'   => $candidate->name,
            'email'  => $candidate->email,
            'skills' => $candidate->skills
        ]);

        $candidate->id = $model->id;

        return $candidate;
    }
}
