<?php

use App\Infrastructure\Controllers\CandidateController;
use App\Infrastructure\Controllers\UserController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    Route::post('/user', [ UserController::class, 'store' ]);
    Route::post('/', function (): Carbon{
        return Carbon::now();
    });
});
