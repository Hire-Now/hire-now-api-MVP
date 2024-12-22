<?php

namespace Tests\Unit\Candidate;

use App\Application\UseCases\CandidateUseCase;
use App\Domain\Repositories\CandidateRepositoryInterface;
use App\Domain\Services\CandidateService;
use App\Application\DTOs\Candidate\CandidateDTO;
use PHPUnit\Framework\TestCase;

class CandidateUseCaseTest extends TestCase
{
    public function testExecute()
    {
        $repository = $this->createMock(CandidateRepositoryInterface::class);
        $service = $this->createMock(CandidateService::class);
        $useCase = new CandidateUseCase($repository, $service);

        $dto = new CandidateDTO(name: 'John Doe', email: 'john@example.com', skills: 'PHP, Laravel');

        $repository->expects($this->once())
            ->method('save')
            ->willReturn(new \App\Domain\Entities\Candidate(1, 'John Doe', 'john@example.com', 'PHP, Laravel'));

        $result = $useCase->execute($dto);

        $this->assertEquals('John Doe', $result->name);
    }
}
