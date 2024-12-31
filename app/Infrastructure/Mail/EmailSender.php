<?php

namespace App\Infrastructure\Mail;

use App\Domain\Contracts\EmailSenderInterface;
use Resend\Laravel\Facades\Resend;

class EmailSender implements EmailSenderInterface
{
    public function sendVerificationEmail(string $to, string $link): bool
    {
        $response = Resend::emails()->send([
            'from'    => config('mail.from.address'),
            'to'      => [ $to ],
            'subject' => 'Go Hire Now - Verify Your Email Address',
            'html'    => view('emails.verify-email', [ 'verifyLink' => $link ])->render(),
        ]);

        return isset($response['id']);
    }
}
