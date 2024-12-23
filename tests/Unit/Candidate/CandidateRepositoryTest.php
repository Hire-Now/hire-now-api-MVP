<?php

namespace Tests\Unit\Candidate;

use App\Domain\Entities\Candidate;
use App\Infrastructure\Persistence\Eloquent\CandidateRepository;
use App\Infrastructure\Persistence\Eloquent\Models\Candidate as CandidateModel;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class CandidateRepositoryTest extends TestCase
{
    public function testSave()
    {
        $candidateMock = $this->createMock(Candidate::class);
        $candidateMock->method('getName')->willReturn('John Doe');
        $candidateMock->method('getEmail')->willReturn('john@example.com');
        $candidateMock->method('getSkills')->willReturn('PHP');

        $modelMock = $this->createMock(CandidateModel::class);
        $modelMock->method('save')->willReturn(true);
        $modelMock->id = 100;
        $modelMock->name = 'John Doe';
        $modelMock->email = 'john@example.com';
        $modelMock->skills = 'PHP';

        $repository = new CandidateRepository();

        $saved = $repository->save($candidateMock);

        // $this->assertNotNull($saved->getId());
        $this->assertEquals('John Doe', $saved->getName());
    }
}
