<?php

namespace App\Infrastructure\Providers;

use App\Application\Contracts\AuthorizationInterface;
use App\Application\Contracts\ConsumerAuthInterface;
use App\Application\Contracts\ConsumerRepositoryPort;

use App\Application\Ports\Inbound\CandidateManagementPort;
use App\Application\Ports\Inbound\FileManagementPort;
use App\Application\Ports\Inbound\PermissionManagementPort;
use App\Application\Ports\Inbound\RoleManagementPort;
use App\Application\Services\AuthorizationService;
use App\Application\Services\ConsumerAuthService;
use App\Application\UseCases\CandidateUseCase;
use App\Application\UseCases\FileUseCase;
use App\Application\UseCases\PermissionUseCase;
use App\Application\UseCases\RoleUseCase;

use App\Domain\Ports\Outbound\CandidateRepositoryPort;
use App\Domain\Ports\Outbound\EmailSenderPort;
use App\Domain\Ports\Outbound\EmailVerificationRepositoryPort;
use App\Domain\Ports\Outbound\FileRepositoryPort;
use App\Domain\Ports\Outbound\JWTServicePort;
use App\Domain\Ports\Outbound\JWTTokenRepositoryPort;
use App\Domain\Ports\Outbound\PasswordHasherPort;
use App\Domain\Ports\Outbound\PermissionRepositoryPort;
use App\Domain\Ports\Outbound\RoleRepositoryPort;
use App\Domain\Ports\Outbound\TokenGeneratorPort;
use App\Domain\Ports\Outbound\UserRepositoryPort;

use App\Infrastructure\Mail\EmailSender;
use App\Infrastructure\Persistence\Eloquent\CandidateRepository;
use App\Infrastructure\Persistence\Eloquent\ConsumerRepository;
use App\Infrastructure\Persistence\Eloquent\EmailVerificationRepository;
use App\Infrastructure\Persistence\Eloquent\FileRepository;
use App\Infrastructure\Persistence\Eloquent\JwtTokenRepository;

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
        $this->app->bind(UserRepositoryPort::class, UserRepository::class);

        $this->app->bind(CandidateRepositoryPort::class, CandidateRepository::class);
        $this->app->bind(CandidateManagementPort::class, CandidateUseCase::class);

        $this->app->bind(EmailSenderPort::class, EmailSender::class);
        $this->app->bind(EmailVerificationRepositoryPort::class, EmailVerificationRepository::class);

        $this->app->bind(PasswordHasherPort::class, BcryptPasswordHasher::class);
        $this->app->bind(TokenGeneratorPort::class, TokenGenerator::class);

        $this->app->bind(RoleManagementPort::class, RoleUseCase::class);
        $this->app->bind(RoleRepositoryPort::class, RoleRepository::class);

        $this->app->bind(PermissionManagementPort::class, PermissionUseCase::class);
        $this->app->bind(PermissionRepositoryPort::class, PermissionRepository::class);

        $this->app->bind(JWTServicePort::class, JWTService::class);
        $this->app->bind(JWTTokenRepositoryPort::class, JwtTokenRepository::class);

        $this->app->bind(AuthorizationInterface::class, AuthorizationService::class);

        $this->app->bind(ConsumerRepositoryPort::class, ConsumerRepository::class);
        $this->app->bind(ConsumerAuthInterface::class, ConsumerAuthService::class);

        $this->app->bind(FileManagementPort::class, FileUseCase::class);
        $this->app->bind(FileRepositoryPort::class, FileRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        QueryLoggerService::enable();
    }
}
