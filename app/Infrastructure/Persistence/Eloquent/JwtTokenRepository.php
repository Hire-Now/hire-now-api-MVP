<?php

namespace App\Infrastructure\Persistence\Eloquent;

use Exception;
use Carbon\Carbon;
use App\Domain\Entities\JwtToken;
use App\Domain\Entities\EmailVerification;
use App\Domain\Entities\Permission;
use App\Domain\Entities\Role;
use App\Domain\Entities\User;
use App\Domain\Enums\ElementStatus;
use Illuminate\Database\Eloquent\Collection;
use App\Domain\Repositories\JwtTokenRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Infrastructure\Persistence\Eloquent\Models\EmailVerificationToken;
use App\Infrastructure\Persistence\Eloquent\Models\JwtToken as ModelsJwtToken;

class JwtTokenRepository implements JwtTokenRepositoryInterface
{
    public function create(JwtToken $entity): JwtToken
    {
        try {
            $jwtTokenModel = ModelsJwtToken::create([
                'user_id'     => $entity->getUserId(),
                'jti'         => $entity->getJti(),
                'status'      => 'valid',
                'expiry_time' => $entity->getExpiryTime(),
                'type_time'   => $entity->getTypeTime(),
            ]);

            $entity->setId($jwtTokenModel->id);

            return $entity;
        } catch (\Throwable $th) {
            throw new Exception("Error saving record on the database", 0, $th);
        }
    }

    public function findByJtiAndUserId(string $jti, string $userId, string $status = 'valid'): array
    {
        try {
            $jwtTokenModel = ModelsJwtToken::where([
                'jti'     => $jti,
                'user_id' => $userId,
                'status'  => $status
            ])->with('user.roles.permissions')->firstOrFail();

            $roleEntities = [];

            foreach ($jwtTokenModel->user->roles ?? [] as $role) {
                $permissionArray = [];

                foreach ($role->permissions ?? [] as $permission) {
                    $permissionArray[] = new Permission($permission->id, $permission->name, $permission->description);
                }

                $roleEntities[] = new Role($role->id, $role->name, $role->description, $permissionArray);
            }

            $entity = new User(
                $userId,
                $jwtTokenModel->user->name,
                $jwtTokenModel->user->email,
                $jwtTokenModel->user->password,
                $jwtTokenModel->user->birth_date,
                $roleEntities,
                ElementStatus::{strtoupper($jwtTokenModel->user->status)},
                $jwtTokenModel->user->created_at,
                Carbon::now()
            );

            return [
                'model'  => $jwtTokenModel->user,
                'entity' => $entity
            ];
        } catch (ModelNotFoundException $th) {
            throw new ModelNotFoundException("No records found, invalid token.", 0, $th);
        } catch (\Throwable $th) {
            throw new Exception("Error processing JWT token data.", 0, $th);
        }
    }

    public function findById(string $id): ?JwtToken
    {
        return null;
    }

    public function update(string $id, JwtToken $entity): JwtToken
    {
        //
    }
    public function delete(string $id): JwtToken
    {
        //
    }

}
