<?php

namespace Tests\Unit\Candidate;

use App\Domain\Entities\Candidate;
use App\Infrastructure\Persistence\Eloquent\CandidateRepository;
use App\Infrastructure\Persistence\Eloquent\Models\Candidate as CandidateModel;
use BadMethodCallException;
use Tests\TestCase;

class CandidateRepositoryTest extends TestCase
{
    public function testSave()
    {
        $candidateMock = $this->createMock(Candidate::class);

        $candidateMock->expects($this->any())
            ->method('__call')
            ->willReturnCallback(fn(string $name, array $arguments): mixed => match ($name) {
                'getName' => 'John Doe',
                'getEmail' => 'john@example.com',
                'getSkills' => 'PHP',
                'getId' => '',
                default => throw new BadMethodCallException("Method {$name} is not mocked"),
            });

        $modelMock = $this->createMock(CandidateModel::class);
        $modelMock->method('save')->willReturn(true);
        $modelMock->id = 1;
        $modelMock->name = 'John Doe';
        $modelMock->email = 'john@example.com';
        $modelMock->skills = 'PHP';

        $repository = new CandidateRepository();

        $saved = $repository->save($candidateMock);

        $this->assertEquals('John Doe', $saved->getName());
        $this->assertEquals('john@example.com', $saved->getEmail());
        $this->assertEquals('PHP', $saved->getSkills());
    }
}
