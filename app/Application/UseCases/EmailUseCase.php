<?php

namespace App\Application\UseCases;

use App\Domain\Contracts\EmailSenderInterface;
use App\Domain\Entities\EmailVerification;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Entities\User;
use App\Domain\Repositories\EmailVerificationRepositoryInterface;
use App\Domain\Contracts\TokenGeneratorInterface;
use Exception;
use Illuminate\Support\Facades\Mail;

class EmailUseCase
{
    public function __construct(private EmailVerificationRepositoryInterface $repository, private TokenGeneratorInterface $tokenGenerator, private EmailSenderInterface $emailSender)
    {
    }

    // public function verifyEmail(): string
    // {
    //     $hashedPassword = $this->tokenGenerator->generateVerificationToken();

    //     $entity->setPassword($hashedPassword);

    //     return $this->repository->create($entity);
    // }

    public function generateEmailVerifyLink(EmailVerification $entity): EmailVerification
    {
        $verifyLink = config('app.url') . "api/v1/user/email/verify/" . $entity->getUserId() . "/" . $this->tokenGenerator->generateVerificationToken();

        $entity->setVerifyLink($verifyLink);
        $entity->setHash($this->tokenGenerator->generateVerificationToken());

        $entity = $this->repository->create($entity);

        return $entity;
    }

    public function sendEmailVerificationLink(EmailVerification $entity): EmailVerification
    {
        try {
            $emailSent = $this->emailSender->sendVerificationEmail($entity->getEmail(), $entity->getVerifyLink());

            if (!$emailSent) {
                throw new Exception('Email could not be sent.');
            }

            return $entity;
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), 0, $th);
        }
    }
}
