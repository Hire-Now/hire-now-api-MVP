<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Application\Contracts\ConsumerRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Adapters\UserAdapter;
use Exception;
use Carbon\Carbon;
use App\Domain\Entities\JwtToken;
use App\Domain\Entities\Permission;
use App\Domain\Entities\Role;
use App\Domain\Entities\User;
use App\Domain\Enums\ElementStatus;

use App\Domain\Repositories\JwtTokenRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Adapters\ConsumerAdapter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Infrastructure\Persistence\Eloquent\Models\JwtToken as ModelsJwtToken;

class JwtTokenRepository implements JwtTokenRepositoryInterface
{
    public function __construct(private readonly UserRepositoryInterface $userRepositoryInterface, private readonly ConsumerRepositoryInterface $consumerRepositoryInterface)
    {
    }

    public function create(JwtToken $entity): JwtToken
    {
        try {
            $jwtTokenModel = ModelsJwtToken::create([
                'entity_id'   => $entity->getEntityId(),
                'jti'         => $entity->getJti(),
                'status'      => 'valid',
                'expiry_time' => $entity->getExpiryTime(),
                'type_time'   => $entity->getTypeTime(),
                'owner'       => $entity->getOwner()
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
                'jti'       => $jti,
                'entity_id' => $userId,
                'status'    => $status
            ])->firstOrFail();

            $entity = $jwtTokenModel->owner === 'User' ?
                $this->userRepositoryInterface->findById($userId) : $this->consumerRepositoryInterface->findById($userId);

            return [
                'model'  => $jwtTokenModel->owner === 'User' ? UserAdapter::toEloquent($entity) : ConsumerAdapter::toEloquent($entity),
                'entity' => $entity
            ];
        } catch (ModelNotFoundException $th) {
            throw new ModelNotFoundException("No records found, invalid token.", 0, $th);
        } catch (\Throwable $th) {
            throw new Exception("Error processing JWT token data.", 0, $th);
        }
    }
}
