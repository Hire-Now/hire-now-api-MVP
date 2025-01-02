<?php

namespace App\Infrastructure\Controllers;

use App\Application\Commands\Permission\CreatePermissionCommand;
use App\Application\Commands\Permission\ListPermissionsCommand;
use App\Application\Handlers\Permission\CreatePermissionCommandHandler;
use App\Application\Handlers\Permission\ListPermissionsCommandHandler;
use App\Domain\Entities\Role;
use App\Infrastructure\Requests\CreatePermissionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{

    public function __construct(
        private CreatePermissionCommandHandler $createPermissionCommandHandler,
        private ListPermissionsCommandHandler $listPermissionsCommandHandler
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $command = new ListPermissionsCommand(
            $request->query('name') ?? null,
            $request->query('status') ?? null,
            $request->query('order_by') ?? 'created',
            $request->query('order_direction') ?? 'asc',
        );

        $permissions = $this->listPermissionsCommandHandler->handle($command);

        return response()->json([
            'status'  => 'SUCCESS',
            'message' => 'Permissions obtained successfully!',
            'data'    => $permissions
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(CreatePermissionRequest $request): JsonResponse
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
