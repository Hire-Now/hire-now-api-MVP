<?php

use App\Infrastructure\Controllers\CandidateController;
use App\Infrastructure\Controllers\PermissionsController;
use App\Infrastructure\Controllers\RoleController;
use App\Infrastructure\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware([ 'throttle:6,1' ])->group(function () {
    Route::post('user/authenticate', [ UserController::class, 'authenticate' ]);

    //todo: asignar roles a usuario (editar los roles del usuario)
    Route::resource('user', UserController::class)
        ->only([ 'index', 'show', 'store', 'update', 'destroy' ]);

    Route::get('user/email/verify/{id}/{hash}', [ UserController::class, 'verifyEmail' ])
        ->name('email.verify')
        ->middleware([ 'signed' ]);

    Route::resource('candidate', UserController::class)
        ->only([ 'index', 'show', 'store', 'update', 'destroy' ]);

    Route::prefix('admin')->group(function () {
        Route::resource('role', RoleController::class)
            ->only([ 'index', 'show', 'store', 'update', 'destroy' ]);

        Route::post('role/{roleId}/permission', [ RoleController::class, 'assignPermissionToRole' ]);

        Route::resource('permission', PermissionsController::class)
            ->only([ 'index', 'show', 'store', 'update', 'destroy' ]);
    });//todo: middleware de auth y de role adm.
});
