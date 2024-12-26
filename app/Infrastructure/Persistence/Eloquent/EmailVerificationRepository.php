<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Entities\EmailVerification;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Entities\User;
use App\Domain\Repositories\EmailVerificationRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\EmailVerificationToken;
use App\Infrastructure\Persistence\Eloquent\Models\User as UserModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmailVerificationRepository implements EmailVerificationRepositoryInterface
{
    public function create(EmailVerification $entity): EmailVerification
    {
        try {
            $emailVerifyModel = EmailVerificationToken::create([
                'user_id' => $entity->getUserId(),
                'email' => $entity->getEmail(),
                'token' => $entity->getHash()
            ]);

            $entity->setId($emailVerifyModel->id);

            return $entity;
        } catch (\Throwable $th) {
            throw new Exception("Error saving record on the database", 0, $th);
        }
    }

    public function findByIdAndHash(EmailVerification $entity): ?EmailVerification
    {
        try {
            $emailVerifyModel = EmailVerificationToken::where([
                'user_id' => $entity->getUserId(),
                'email'   => $entity->getEmail(),
                'token'   => $entity->getHash()
            ])->first();


            if (!is_object($emailVerifyModel)) {
                throw new ModelNotFoundException();
            }

            return $entity;
        } catch (ModelNotFoundException $th) {
            throw new ModelNotFoundException("No records found, invalid token.", 0, $th);
        } catch (\Throwable $th) {
            throw new Exception("Error saving record on the database", 0, $th);
        }
    }
}
