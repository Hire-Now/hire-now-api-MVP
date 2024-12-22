<?php

namespace Tests\Unit\Candidate;

use App\Infrastructure\Controllers\CandidateController;
use App\Application\UseCases\CandidateUseCase;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Mockery;

class CandidateControllerTest extends TestCase
{
    public function testStore()
    {
        $useCaseMock = Mockery::mock(CandidateUseCase::class);
        $useCaseMock->shouldReceive('execute')
            ->once()
            ->andReturn(new \App\Domain\Entities\Candidate(1, 'Test'));

        $controller = new CandidateController($useCaseMock);

        $request = Request::create('/store', 'POST', ['name' => 'Test']);
        $response = $controller->store($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('Test created successfully!', $response->getContent());
    }
}
