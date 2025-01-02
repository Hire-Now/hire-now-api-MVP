<?php

use Illuminate\Support\Facades\Route;
use App\Application\Middlewares\JwtAuthMiddleware;
use App\Infrastructure\Controllers\RoleController;
use App\Infrastructure\Controllers\UserController;
use App\Infrastructure\Controllers\CandidateController;
use App\Infrastructure\Controllers\PermissionsController;

Route::prefix('v1')->middleware([ 'throttle:6,1', JwtAuthMiddleware::class])->group(function () {
    Route::post('user/authenticate', [ UserController::class, 'authenticate' ])->withoutMiddleware([ JwtAuthMiddleware::class]);

    Route::resource('user', UserController::class)
        ->only([ 'index', 'show', 'store', 'update', 'destroy' ]);

    Route::get('user/email/verify/{id}/{hash}', [ UserController::class, 'verifyEmail' ])
        ->name('email.verify')
        ->middleware([ 'signed' ])->withoutMiddleware([ JwtAuthMiddleware::class]);

    Route::resource('candidate', UserController::class)
        ->only([ 'index', 'show', 'store', 'update', 'destroy' ]);

    Route::prefix('admin')->group(function () {
        Route::resource('role', RoleController::class)
            ->only([ 'index', 'show', 'store', 'update', 'destroy' ]);

        Route::post('role/{roleId}/permission', [ RoleController::class, 'assignPermissionToRole' ]);

        Route::resource('permission', PermissionsController::class)
            ->only([ 'index', 'show', 'store', 'update', 'destroy' ]);
    });
});
