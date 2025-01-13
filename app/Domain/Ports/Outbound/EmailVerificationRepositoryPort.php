<?php

namespace App\Domain\Ports\Outbound;

use App\Domain\Entities\EmailVerification;

interface EmailVerificationRepositoryPort
{
    public function create(EmailVerification $entity);
    public function findByIdAndHash(EmailVerification $entity): ?EmailVerification;
    public function update(string $id, EmailVerification $entity): EmailVerification;
}
