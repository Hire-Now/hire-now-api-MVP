<?php
namespace Tests\Unit\Candidate;

use App\Application\UseCases\CandidateUseCase;
use App\Domain\Repositories\CandidateRepositoryInterface;
use App\Infrastructure\Controllers\CandidateController;
use App\Infrastructure\Services\CandidateService;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class CandidateControllerTest extends TestCase
{
    public function testStore()
    {
        /** @var CandidateRepositoryInterface|\Mockery\MockInterface $mockRepository */
        $mockRepository = Mockery::mock(CandidateRepositoryInterface::class);
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
