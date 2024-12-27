<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Entities\EmailVerification;
use App\Domain\Repositories\EmailVerificationRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\EmailVerificationToken;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class EmailVerificationRepository implements EmailVerificationRepositoryInterface
{
    public function create(EmailVerification $entity): EmailVerification
    {
        try {
            $emailVerifyModel = EmailVerificationToken::create([
                'user_id' => $entity->getUserId(),
                'email'   => $entity->getEmail(),
                'token'   => $entity->getStoredHash()
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
                'token'   => $entity->getUrlHash()
            ])->first();

            if (!is_object($emailVerifyModel)) {
                throw new ModelNotFoundException();
            }

            $entity->setId($emailVerifyModel->id);
            $entity->setStoredHash($emailVerifyModel->token);
            $entity->setEmailVerifiedAt($emailVerifyModel->email_verified_at);

            return $entity;
        } catch (ModelNotFoundException $th) {
            throw new ModelNotFoundException("No records found, invalid token.", 0, $th);
        } catch (\Throwable $th) {
            throw new Exception("Error saving record on the database", 0, $th);
        }
    }

    public function findById(string $id): ?EmailVerification
    {
        return null;
    }

    public function update(string $id, EmailVerification $entity): EmailVerification
    {
        try {
            $date = Carbon::now();

            $emailVerifyModel = EmailVerificationToken::where('id', $id)->update([
                'email'             => $entity->getEmail(),
                'token'             => $entity->getStoredHash(),
                'email_verified_at' => $date
            ]);

            if (!$emailVerifyModel) {
                throw new ModelNotFoundException('An error happened when updating the model, ' . EmailVerificationToken::class);
            }

            $entity->setEmailVerifiedAt($date);

            return $entity;
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), 0, $th);
        }
    }

    public function delete(string $id): EmailVerification
    {
        return new EmailVerification();
    }

    public function findByEmail(string $email): EmailVerification
    {
        return new EmailVerification();
    }

    public function fetchAll(): Collection
    {
        return new Collection();
    }

    public function paginate(int $perPage): Collection
    {
        return new Collection();
    }

}
