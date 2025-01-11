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

    }

    public function boot()
    {
        //Code here
    }
}
