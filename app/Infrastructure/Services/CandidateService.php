<?php

namespace App\Infrastructure\Services;

use Illuminate\Support\Facades\Mail;
use App\Domain\Services\CandidateService as DomainCandidateService;

class CandidateService extends DomainCandidateService
{
    public function sendNotification(string $email, string $message)
    {
        Mail::raw($message, function ($mail) use ($email) {
            $mail->to($email)
                ->subject('Notification');
        });
    }
}
