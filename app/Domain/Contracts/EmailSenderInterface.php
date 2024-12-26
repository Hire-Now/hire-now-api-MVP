<?php

namespace App\Domain\Contracts;

use Illuminate\Mail\SentMessage;

interface EmailSenderInterface
{
    public function sendVerificationEmail(string $to, string $link): bool;
}
