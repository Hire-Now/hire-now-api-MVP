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
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\UnauthorizedException;

class EmailUseCase
{
    public function __construct(private EmailVerificationRepositoryInterface $repository, private TokenGeneratorInterface $tokenGenerator, private EmailSenderInterface $emailSender)
    {
    }

    public function generateEmailVerifyLink(EmailVerification $entity): EmailVerification
    {
        $hash = $this->tokenGenerator->generateVerificationToken();

        $verifyLink = URL::signedRoute('email.verify', [ 'id' => $entity->getUserId(), 'hash' => $hash ]);

        $entity->setVerifyLink($verifyLink);
        $entity->setStoredHash($hash);

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

    public function verifyEmail(EmailVerification $entity): EmailVerification
    {
        try {
            $entity = $this->repository->findByIdAndHash($entity);

            $isTokenValid = hash_equals($entity->getStoredHash(), $entity->getUrlHash());

            if (!$isTokenValid) {
                throw new UnauthorizedException('Invalid email verification token.');
            }

            return $this->repository->update($entity->getId(), $entity);
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), 0, $th);
        }
    }
}
