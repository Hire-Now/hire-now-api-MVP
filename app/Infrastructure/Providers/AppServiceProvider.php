<?php

namespace App\Infrastructure\Providers;

use App\Application\Contracts\AuthorizationInterface;
use App\Application\Contracts\ConsumerAuthInterface;
use App\Application\Contracts\ConsumerRepositoryInterface;
use App\Application\Contracts\PermissionUseCaseInterface;
use App\Application\Contracts\RoleUseCaseInterface;
use App\Application\Services\AuthorizationService;
use App\Application\Services\ConsumerAuthService;
use App\Application\UseCases\PermissionUseCase;
use App\Application\UseCases\RoleUseCase;

use App\Domain\Contracts\EmailSenderInterface;
use App\Domain\Contracts\JWTServiceInterface;
use App\Domain\Contracts\PasswordHasherInterface;
use App\Domain\Contracts\TokenGeneratorInterface;
use App\Domain\Policies\UsersPolicy;
use App\Domain\Repositories\CandidateRepositoryInterface;
use App\Domain\Repositories\EmailVerificationRepositoryInterface;
use App\Domain\Repositories\JwtTokenRepositoryInterface;
use App\Domain\Repositories\PermissionRepositoryInterface;
use App\Domain\Repositories\RoleRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;

use App\Infrastructure\Mail\EmailSender;
use App\Infrastructure\Persistence\Eloquent\CandidateRepository;
use App\Infrastructure\Persistence\Eloquent\ConsumerRepository;
use App\Infrastructure\Persistence\Eloquent\EmailVerificationRepository;
use App\Infrastructure\Persistence\Eloquent\JwtTokenRepository;
use App\Infrastructure\Persistence\Eloquent\Models\User;
use App\Infrastructure\Persistence\Eloquent\PermissionRepository;
use App\Infrastructure\Persistence\Eloquent\RoleRepository;
use App\Infrastructure\Persistence\Eloquent\UserRepository;
use App\Infrastructure\Services\BcryptPasswordHasher;
use App\Infrastructure\Services\JWTService;
use App\Infrastructure\Services\QueryLoggerService;
use App\Infrastructure\Services\TokenGenerator;
use Illuminate\Support\Facades\Gate;
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

        //Roles system
        $this->app->bind(RoleUseCaseInterface::class, RoleUseCase::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(PermissionUseCaseInterface::class, PermissionUseCase::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(ConsumerRepositoryInterface::class, ConsumerRepository::class);

        $this->app->bind(JWTServiceInterface::class, JWTService::class);
        $this->app->bind(JwtTokenRepositoryInterface::class, JwtTokenRepository::class);
        $this->app->bind(AuthorizationInterface::class, AuthorizationService::class);
        $this->app->bind(ConsumerAuthInterface::class, ConsumerAuthService::class);


    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        QueryLoggerService::enable();
    }
}
