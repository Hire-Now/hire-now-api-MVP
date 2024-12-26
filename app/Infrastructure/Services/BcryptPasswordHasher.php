<?php

namespace App\Infrastructure\Services;

use App\Domain\Contracts\PasswordHasherInterface;
use Illuminate\Support\Facades\Hash;

class BcryptPasswordHasher implements PasswordHasherInterface
{
    public function hash(string $password): string
    {
        return Hash::make($password, [ 'rounds' => 12 ]);
    }

    public function verify(string $password, string $hashedPassword): bool
    {
        return Hash::check($password, $hashedPassword);
    }
}
