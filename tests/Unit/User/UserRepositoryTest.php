<?php

namespace Tests\Unit\User;

use App\Infrastructure\Persistence\Eloquent\UserRepository;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\User as UserModel;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    public function testSave()
    {
        $model = new UserModel();
        $model->name = 'Test';
        $model->save();

        $repository = new UserRepository();
        $entity = $repository->save(new \App\Domain\Entities\User(null, 'Test'));

        $this->assertEquals('Test', $entity->name);
    }
}
