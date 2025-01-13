<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Application\Contracts\ConsumerRepositoryPort;
use App\Infrastructure\Persistence\Eloquent\Adapters\UserAdapter;
use Exception;
use App\Domain\Entities\JwtToken;
use App\Domain\Ports\Outbound\JWTTokenRepositoryPort;
use App\Domain\Ports\Outbound\UserRepositoryPort;
use App\Infrastructure\Persistence\Eloquent\Adapters\ConsumerAdapter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Infrastructure\Persistence\Eloquent\Models\JwtToken as ModelsJwtToken;

class JwtTokenRepository implements JWTTokenRepositoryPort
{
    public function __construct(private readonly UserRepositoryPort $userRepository, private readonly ConsumerRepositoryPort $consumerRepository)
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
                $this->userRepository->findById($userId) : $this->consumerRepository->findById($userId);

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
