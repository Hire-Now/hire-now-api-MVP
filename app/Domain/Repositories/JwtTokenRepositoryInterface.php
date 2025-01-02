<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\User;
use App\Domain\Entities\JwtToken;
use Illuminate\Database\Eloquent\Collection;

interface JwtTokenRepositoryInterface
{
    public function create(JwtToken $entity): JwtToken;
    public function findByJtiAndUserId(string $jti, string $userId, string $status = 'valid'): ?User;
    public function findById(string $id): ?JwtToken;
    public function update(string $id, JwtToken $entity): JwtToken;
    public function delete(string $id): JwtToken;
}
