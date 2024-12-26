<?php

namespace App\Application\Handlers\User;

use App\Application\Commands\Email\CreateEmailVerificationCommand;
use App\Application\Commands\Email\SendVerificationEmailCommand;
use App\Application\Commands\User\VerifyEmailCommand;
use App\Application\UseCases\EmailUseCase;
use App\Application\UseCases\UserUseCase;
use App\Domain\Entities\EmailVerification;
use App\Domain\Entities\User;
use App\Domain\Enums\ElementStatus;
use Carbon\Carbon;

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
            null
        ));
    }

    public function sendVerificationEmail(SendVerificationEmailCommand $command): EmailVerification
    {
        return $this->emailUseCase->sendEmailVerificationLink($command->getEmailVerification());
    }
}
