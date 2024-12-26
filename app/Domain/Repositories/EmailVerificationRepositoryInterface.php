<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\EmailVerification;
use App\Domain\Entities\User;
use Illuminate\Database\Eloquent\Collection;

interface EmailVerificationRepositoryInterface
{
    public function create(EmailVerification $entity): EmailVerification;
    public function findByIdAndHash(EmailVerification $entity): ?EmailVerification;
}
