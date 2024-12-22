<?php

namespace Tests\Unit;

use App\Application\UseCases\CandidateUseCase;
use App\Domain\Repositories\CandidateRepositoryInterface;
use App\Application\DTOs\CandidateDTO;
use PHPUnit\Framework\TestCase;
use Mockery;

class CandidateUseCaseTest extends TestCase
{
    public function testExecute()
    {
        $repositoryMock = Mockery::mock(CandidateRepositoryInterface::class);
        $repositoryMock->shouldReceive('save')
            ->once()
            ->andReturn(new \App\Domain\Entities\Candidate(1, 'Test'));

        $useCase = new CandidateUseCase($repositoryMock);
        $dto = new CandidateDTO('Test');
        $result = $useCase->execute($dto);

        $this->assertInstanceOf(\App\Domain\Entities\Candidate::class, $result);
        $this->assertEquals('Test', $result->name);
    }
}