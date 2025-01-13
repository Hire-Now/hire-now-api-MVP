<?php

namespace Tests\Unit\User;

use App\Application\UseCases\UserUseCase;
use App\Domain\Repositories\UserRepositoryInterface;
use Tests\TestCase;
use Mockery;

class UserUseCaseTest extends TestCase
{
    // public function testExecute()
    // {
    //     $repositoryMock = Mockery::mock(UserRepositoryInterface::class);
    //     $repositoryMock->shouldReceive('save')
    //         ->once()
    //         ->andReturn(new \App\Domain\Entities\User(1, 'Test'));

    //     $useCase = new UserUseCase($repositoryMock);
    //     $dto = new UserDTO('Test');
    //     $result = $useCase->execute($dto);

    //     $this->assertInstanceOf(\App\Domain\Entities\User::class, $result);
    //     $this->assertEquals('Test', $result->name);
    // }
}
