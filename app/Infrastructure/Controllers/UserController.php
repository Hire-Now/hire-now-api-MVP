<?php

namespace App\Infrastructure\Controllers;

use App\Application\DTOs\User\UserDTO;
use App\Application\UseCases\UserUseCase;
use Illuminate\Http\Request;

class UserController
{
    public function __construct(private UserUseCase $useCase) {}

    public function store(Request $request)
    {
        $dto = UserDTO::fromRequest($request->all());
        $entity = $this->useCase->execute($dto);

        return response()->json([
            'message' => 'User created successfully!',
            'User' => $entity
        ]);
    }
}
