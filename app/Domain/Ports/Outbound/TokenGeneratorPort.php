<?php

namespace App\Domain\Ports\Outbound;

interface TokenGeneratorPort
{
    public function generateVerificationToken(): string;
}
