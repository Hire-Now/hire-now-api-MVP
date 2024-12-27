<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\EmailVerification;
use Illuminate\Database\Eloquent\Collection;

interface EmailVerificationRepositoryInterface
{
    public function create(EmailVerification $entity): EmailVerification;
    public function findByIdAndHash(EmailVerification $entity): ?EmailVerification;
    public function findById(string $id): ?EmailVerification;
    public function update(string $id, EmailVerification $entity): EmailVerification;
    public function delete(string $id): EmailVerification;
    public function findByEmail(string $email): EmailVerification;
    public function fetchAll(): Collection;
    public function paginate(int $perPage): Collection;
}
