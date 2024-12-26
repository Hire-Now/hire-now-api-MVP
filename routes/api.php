<?php

use App\Infrastructure\Controllers\CandidateController;
use App\Infrastructure\Controllers\UserController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    Route::group(['prefix' => 'user'], function(){
        Route::post('/', [ UserController::class, 'store' ])->middleware([ 'throttle:6,1' ]);
        Route::get('/email/verify/{id}/{hash}', [ UserController::class, 'verifyEmail' ])->middleware([ 'signed', 'throttle:6,1' ]);
    });
});
