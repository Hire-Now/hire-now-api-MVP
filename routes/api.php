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
        Route::post('/role', [ RoleController::class, 'create' ]);
        Route::put('/role', [ RoleController::class, 'update' ]);
        Route::get('/role', [ RoleController::class, 'index' ]);
        Route::get('/role/{id}', [ RoleController::class, 'show' ]);
        Route::delete('/role/{id}', [ RoleController::class, 'delete' ]);
        Route::post('/role/{roleId}/permission', [ RoleController::class, 'assignPermissionToRole' ]);

        Route::post('/permission', [ PermissionsController::class, 'create' ]);
        Route::put('/permission', [ PermissionsController::class, 'update' ]);
        Route::get('/permission', [ PermissionsController::class, 'index' ]);
        Route::get('/permission/{id}', [ PermissionsController::class, 'show' ]);
        Route::delete('/permission/{id}', [ PermissionsController::class, 'delete' ]);
    });
})->middleware([ 'throttle:6,1' ]);
