<?php

namespace App\Infrastructure\Controllers;

use App\Application\Commands\Role\AssignPermissionToRoleCommand;
use App\Application\Commands\Role\CreateRoleCommand;
use App\Application\Commands\Role\ListRolesCommand;
use App\Application\Commands\Role\RemovePermissionToRoleCommand;

use App\Application\Handlers\Role\AssignPermissionToRoleCommandHandler;
use App\Application\Handlers\Role\CreateRoleCommandHandler;
use App\Application\Handlers\Role\ListRolesCommandHandler;
use App\Application\Handlers\Role\RemovePermissionToRoleCommandHandler;

use App\Infrastructure\Requests\AssingPermissionToRoleRequest;
use App\Infrastructure\Requests\CreateRoleRequest;

use App\Domain\Entities\Role;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController
{
    public function __construct(
        private CreateRoleCommandHandler $createRoleCommandHandler,
        private ListRolesCommandHandler $listRolesCommandHandler,
        private AssignPermissionToRoleCommandHandler $assignPermissionToRoleCommandHandler,
        private RemovePermissionToRoleCommandHandler $removePermissionToRoleCommandHandler
    ) {
    }

    public function index(Request $request)
    {
        try {
            $command = new ListRolesCommand(
                $request->query('name') ?? null,
                $request->query('status') ?? null,
                $request->query('order_by') ?? 'created',
                $request->query('order_direction') ?? 'asc',
            );

            $roles = $this->listRolesCommandHandler->handle($command);

            return response()->json([
                'status'  => 'SUCCESS',
                'message' => 'Roles obtained succesfully!',
                'data'    => $roles
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'ERROR',
                'message' => $th->getMessage(),
                'data'    => []
            ], 500);
        }
    }

    public function create(CreateRoleRequest $request): JsonResponse
    {
        try {
            $command = new CreateRoleCommand(
                $request->validated()['name'],
                $request->validated()['description']
            );

            $role = $this->createRoleCommandHandler->handle($command);

            return response()->json([
                'status'  => 'SUCCESS',
                'message' => 'Role created successfully!',
                'data'    => [
                    'role' => $role->toArray(),
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'ERROR',
                'message' => $th->getMessage(),
                'data'    => []
            ], 500);
        }
    }


    public function assignPermissionToRole(AssingPermissionToRoleRequest $request, string $roleId)
    {
        try {
            $command = new AssignPermissionToRoleCommand(
                $roleId,
                $request->validated()['permissions']
            );

            $role = $this->assignPermissionToRoleCommandHandler->handle($command);

            return response()->json([
                'status'  => 'SUCCESS',
                'message' => 'Permissions succesfully assigned to role!',
                'data'    => $role->toArray()
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'ERROR',
                'message' => $th->getMessage(),
                'data'    => []
            ], 500);
        }
    }

    public function removePermissionToRole(AssingPermissionToRoleRequest $request, string $roleId)
    {
        try {
            $command = new RemovePermissionToRoleCommand(
                $roleId,
                $request->validated()['permissions']
            );

            $role = $this->removePermissionToRoleCommandHandler->handle($command);

            return response()->json([
                'status'  => 'SUCCESS',
                'message' => 'Permissions succesfully removed to role!',
                'data'    => $role->toArray()
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'ERROR',
                'message' => $th->getMessage(),
                'data'    => []
            ], 500);
        }
    }

    public function show(Role $role)
    {
        //
    }


    public function update(Request $request, Role $role)
    {
        //
    }

    public function delete(Role $role)
    {
        //
    }
}
