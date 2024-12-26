<?php

namespace App\Infrastructure\Providers;

use App\Domain\Contracts\EmailSenderInterface;
use App\Domain\Contracts\PasswordHasherInterface;
use App\Domain\Contracts\TokenGeneratorInterface;
use App\Domain\Repositories\CandidateRepositoryInterface;
use App\Domain\Repositories\EmailVerificationRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Mail\EmailSender;
use App\Infrastructure\Persistence\Eloquent\CandidateRepository;
use App\Infrastructure\Persistence\Eloquent\EmailVerificationRepository;
use App\Infrastructure\Persistence\Eloquent\UserRepository;
use App\Infrastructure\Services\BcryptPasswordHasher;
use App\Infrastructure\Services\TokenGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(EmailSenderInterface::class, EmailSender::class);
        $this->app->bind(CandidateRepositoryInterface::class, CandidateRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(PasswordHasherInterface::class, BcryptPasswordHasher::class);
        $this->app->bind(EmailVerificationRepositoryInterface::class, EmailVerificationRepository::class);
        $this->app->bind(TokenGeneratorInterface::class, TokenGenerator::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
