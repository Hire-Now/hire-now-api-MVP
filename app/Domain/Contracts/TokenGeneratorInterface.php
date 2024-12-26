<?php

namespace App\Domain\Contracts;

interface TokenGeneratorInterface
{
    public function generateVerificationToken(): string;
}
