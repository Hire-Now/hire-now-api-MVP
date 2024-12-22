<?php

namespace Tests\Unit\Candidate;

use App\Infrastructure\Persistence\Eloquent\CandidateRepository;
use App\Infrastructure\Persistence\Eloquent\Models\Candidate as CandidateModel;
use PHPUnit\Framework\TestCase;

class CandidateRepositoryTest extends TestCase
{
    public function testSave()
    {
        $repository = new CandidateRepository();

        $candidate = new \App\Domain\Entities\Candidate(null, 'John Doe', 'john@example.com', 'PHP, Laravel');

        $saved = $repository->save($candidate);

        $this->assertNotNull($saved->id);
        $this->assertEquals('John Doe', $saved->name);
    }
}
