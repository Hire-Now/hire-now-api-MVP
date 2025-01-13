<?php

namespace App\Domain\Ports\Outbound;

interface PasswordHasherPort
{
    public function hash(string $password): string;
    public function verify(string $password, string $hashedPassword): bool;
}
