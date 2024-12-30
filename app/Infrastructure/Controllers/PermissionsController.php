<?php

namespace App\Infrastructure\Controllers;

use App\Application\Commands\Permission\CreatePermissionCommand;
use App\Application\Handlers\Permission\CreatePermissionCommandHandler;
use App\Domain\Entities\Permission;
use App\Domain\Entities\Role;
use App\Infrastructure\Requests\CreatePermissionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionsController
{

    public function __construct(
        private CreatePermissionCommandHandler $createPermissionCommandHandler,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CreatePermissionRequest $request): JsonResponse
    {
        try {
            $command = new CreatePermissionCommand(
                $request->validated()['name'],
                $request->validated()['description']
            );

            $permission = $this->createPermissionCommandHandler->handle($command);

            return response()->json([
                'status'  => 'SUCCESS',
                'message' => 'Permission created successfully!',
                'data'    => [
                    'role' => $permission->toArray(),
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'data'    => []
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        //
    }
}
