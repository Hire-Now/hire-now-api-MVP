<?php

namespace App\Domain\Ports\Outbound;

interface EmailSenderPort
{
    public function sendVerificationEmail(string $to, string $link): bool;
}
