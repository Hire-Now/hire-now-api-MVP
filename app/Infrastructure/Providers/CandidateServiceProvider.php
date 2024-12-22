<?php

namespace App\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Repositories\CandidateRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\CandidateRepository;
use App\Domain\Services\CandidateService;

class CandidateServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(CandidateRepositoryInterface::class, CandidateRepository::class);
        $this->app->singleton(CandidateService::class, CandidateService::class);
    }

    public function boot()
    {
        //Code here
    }
}
