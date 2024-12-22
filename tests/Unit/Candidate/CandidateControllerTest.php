<?php

namespace Tests\Unit\Candidate;

use App\Infrastructure\Controllers\CandidateController;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class CandidateControllerTest extends TestCase
{
    public function testStore()
    {
        $controller = new CandidateController(new \App\Application\UseCases\CandidateUseCase(
            new \App\Infrastructure\Persistence\Eloquent\CandidateRepository(),
            new \App\Infrastructure\Services\CandidateService()
        ));

        $request = Request::create('/candidates', 'POST', [
            'name'   => 'John Doe',
            'email'  => 'john@example.com',
            'skills' => 'PHP, Laravel'
        ]);

        $response = $controller->store($request);

        $this->assertJson($response->getContent());
    }
}
