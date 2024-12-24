<?php

namespace Tests\Unit\User;

use App\Infrastructure\Controllers\UserController;
use App\Application\UseCases\UserUseCase;
use Illuminate\Http\Request;
use Tests\TestCase;
use Mockery;

class UserControllerTest extends TestCase
{
    public function testStore()
    {
        $useCaseMock = Mockery::mock(UserUseCase::class);
        $useCaseMock->shouldReceive('execute')
            ->once()
            ->andReturn(new \App\Domain\Entities\User(1, 'Test'));

        $controller = new UserController($useCaseMock);

        $request = Request::create('/store', 'POST', ['name' => 'Test']);
        $response = $controller->store($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('Test created successfully!', $response->getContent());
    }
}
