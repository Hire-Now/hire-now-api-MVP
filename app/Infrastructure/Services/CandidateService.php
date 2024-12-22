<?php

namespace App\Infrastructure\Services;

use Illuminate\Support\Facades\Mail;

class CandidateService{
    public function sendNotification(string $email, string $message)
    {
        Mail::raw($message, function ($mail) use ($email) {
            $mail->to($email)
                ->subject('Notification');
        });
    }}