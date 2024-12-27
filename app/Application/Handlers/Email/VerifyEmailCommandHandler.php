<?php

namespace App\Application\Handlers\Email;

use App\Application\Commands\Email\CreateEmailVerificationCommand;
use App\Application\Commands\Email\SendVerificationEmailCommand;
use App\Application\Commands\Email\VerifyEmailCommand;
use App\Application\UseCases\EmailUseCase;

use App\Domain\Entities\EmailVerification;

class VerifyEmailCommandHandler
{
    public function __construct(private EmailUseCase $emailUseCase)
    {
    }

    public function handleLinkGeneration(CreateEmailVerificationCommand $command): EmailVerification
    {
        return $this->emailUseCase->generateEmailVerifyLink(new EmailVerification(
            null,
            $command->getUserId(),
            $command->getEmail(),
            null,
            null,
            null,
            null
        ));
    }

    public function sendVerificationEmail(SendVerificationEmailCommand $command): EmailVerification
    {
        return $this->emailUseCase->sendEmailVerificationLink($command->getEmailVerification());
    }

    public function handleHashVerification(VerifyEmailCommand $command): EmailVerification
    {
        return $this->emailUseCase->verifyEmail(new EmailVerification(
            null,
            $command->getUserId(),
            $command->getEmail(),
            null,
            $command->getHash(),
            null,
            null
        ));
    }
}
