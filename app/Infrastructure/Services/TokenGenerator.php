<?php

namespace App\Infrastructure\Services;

use App\Domain\Ports\Outbound\TokenGeneratorPort;
use Illuminate\Support\Str;

class TokenGenerator implements TokenGeneratorPort
{
    public function generateVerificationToken(): string
    {
        return hash('sha256', random_bytes(32) . uniqid('', true) . microtime(true) . Str::uuid() . gethostname());
    }
}
