<?php

namespace Tests\Unit;

use App\Infrastructure\Persistence\Eloquent\CandidateRepository;
use App\Domain\Repositories\CandidateRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\Candidate as CandidateModel;
use PHPUnit\Framework\TestCase;

class CandidateRepositoryTest extends TestCase
{
    public function testSave()
    {
        $model = new CandidateModel();
        $model->name = 'Test';
        $model->save();

        $repository = new CandidateRepository();
        $entity = $repository->save(new \App\Domain\Entities\Candidate(null, 'Test'));

        $this->assertEquals('Test', $entity->name);
    }
}