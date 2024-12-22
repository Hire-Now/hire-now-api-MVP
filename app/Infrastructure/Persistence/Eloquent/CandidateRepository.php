<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Repositories\CandidateRepositoryInterface;
use App\Domain\Entities\Candidate;
use App\Infrastructure\Persistence\Eloquent\Models\Candidate as CandidateModel;

class CandidateRepository implements CandidateRepositoryInterface
{
    public function save(Candidate $entity): Candidate
    {
        $model = CandidateModel::updateOrCreate(
            ['id' => $entity->id],
            ['name' => $entity->name]
        );

        $entity->id = $model->id;

        return $entity;
    }
}