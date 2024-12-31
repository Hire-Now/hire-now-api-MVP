<?php

use App\Infrastructure\Controllers\CandidateController;
use App\Infrastructure\Controllers\PermissionsController;
use App\Infrastructure\Controllers\RoleController;
use App\Infrastructure\Controllers\UserController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

//todo: middleware que valide que el email ya se encuentra verificado
// todo: middlware de autenticación.
Route::prefix('v1')->group(function () {
    Route::group([ 'prefix' => 'user' ], function () {
        Route::post('/', [ UserController::class, 'store' ]);

        Route::get('/email/verify/{id}/{hash}', [ UserController::class, 'verifyEmail' ])
            ->name('email.verify')
            ->middleware([ 'signed' ]);
    });

    Route::group([ 'prefix' => 'admin' ], function () {
        Route::group([ 'prefix' => 'role' ], function () {
            Route::post('/', [ RoleController::class, 'create' ]);
            Route::put('/', [ RoleController::class, 'update' ]); //todo: falta
            Route::get('/', [ RoleController::class, 'index' ]);
            Route::get('/{id}', [ RoleController::class, 'show' ]); //todo: falta
            Route::delete('/{id}', [ RoleController::class, 'delete' ]); //todo: falta
            Route::post('/{roleId}/permission', [ RoleController::class, 'assignPermissionToRole' ]);
        });

        Route::group([ 'prefix' => 'permission' ], function () {
            Route::post('/', [ PermissionsController::class, 'create' ]);
            Route::put('/', [ PermissionsController::class, 'update' ]); //todo: falta
            Route::get('', [ PermissionsController::class, 'index' ]);
            Route::get('/{id}', [ PermissionsController::class, 'show' ]); //todo: falta
            Route::delete('/{id}', [ PermissionsController::class, 'delete' ]); //todo: falta
        });
    });
})->middleware([ 'throttle:6,1' ]);
