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

    // public function testUpdateCandidate()
    // {
    //     $candidate = new Candidate(id: 1, name: 'John Doe', email: 'johndoe@example.com', skills: 'PHP');
    //     $updatedCandidate = new Candidate(id: 1, name: 'Jane Doe', email: 'janedoe@example.com', skills: 'Laravel');

    //     $repository = \Mockery::mock(CandidateRepositoryInterface::class);
    //     $repository->shouldReceive('find')->with(1)->andReturn($candidate);
    //     $repository->shouldReceive('save')->with(\Mockery::on(function ($arg) use ($updatedCandidate) {
    //         return $arg->name === $updatedCandidate->name;
    //     }))->andReturn($updatedCandidate);

    //     $useCase = new CandidateUseCase($repository, new CandidateService());

    //     $command = new UpdateCandidateCommand(1, 'Jane Doe', 'janedoe@example.com', 'Laravel');
    //     $result = $useCase->update($command);

    //     $this->assertEquals('Jane Doe', $result->name);
    //     $this->assertEquals('janedoe@example.com', $result->email);
    // }
}
