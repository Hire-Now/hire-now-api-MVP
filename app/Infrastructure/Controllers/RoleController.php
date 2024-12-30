<?php

namespace App\Infrastructure\Controllers;

use App\Application\Commands\Role\CreateRoleCommand;
use App\Application\Commands\Role\ListRolesCommand;
use App\Application\Handlers\Role\CreateRoleCommandHandler;
use App\Application\Handlers\Role\ListRolesCommandHandler;
use App\Domain\Entities\Role;
use App\Infrastructure\Requests\CreateRoleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController
{

    public function __construct(
        private CreateRoleCommandHandler $createRoleCommandHandler,
        private ListRolesCommandHandler $listRolesCommandHandler
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $command = new ListRolesCommand(
            $request->query('name') ?? null,
            $request->query('status') ?? null,
            $request->query('order_by') ?? 'created',
            $request->query('order_direction') ?? 'asc',
        );

        $role = $this->listRolesCommandHandler->handle($command);

        return response()->json([
            'status'  => 'SUCCESS',
            'message' => 'Role created successfully!',
            'data'    => [
                'role' => $role,
            ]
        ], 200);
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
                'message' => $th->getMessage(),
                'data'    => []
            ], 500);
        }
    }


    public function assignPermissionToRole(Request $request)
    {
        try {
            // $command = new CreateRoleCommand(
            //     $request->validated()['name'],
            //     $request->validated()['description']
            // );

            // $role = $this->createRoleCommandHandler->handle($command);

            return response()->json([
                'status'  => 'SUCCESS',
                'message' => 'Role created successfully!',
                'data'    => [
                    'role' => [],
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Role $role)
    {
        //
    }
}
