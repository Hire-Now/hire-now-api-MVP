<?php
namespace Tests\Unit\Candidate;

use App\Application\UseCases\CandidateUseCase;
use App\Domain\Repositories\ICandidateRepository;
use App\Infrastructure\Adapter\Inbound\CandidateController;
use App\Infrastructure\Services\CandidateService;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class CandidateControllerTest extends TestCase
{
    public function testStore()
    {
        /** @var ICandidateRepository|\Mockery\MockInterface $mockRepository */
        $mockRepository = Mockery::mock(ICandidateRepository::class);
        $mockRepository->shouldReceive('save')->andReturn(true);

        $useCase = new CandidateUseCase($mockRepository, new CandidateService());

        $controller = new CandidateController($useCase);

        $request = Request::create('/candidates', 'POST', [
            'name'   => 'John Doe',
            'email'  => 'john@example.com',
            'skills' => 'PHP, Laravel'
        ]);

        $response = $controller->store($request);

        $this->assertJson($response->getContent());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
