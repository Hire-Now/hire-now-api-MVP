<?php

use App\Infrastructure\Middlewares\CheckPermissionMiddleware;
use App\Infrastructure\Middlewares\CheckRoleMiddleware;
use App\Infrastructure\Middlewares\ConsumerAuthMiddleware;
use App\Infrastructure\Middlewares\JwtAuthMiddleware;
use App\Infrastructure\Controllers\RoleController;
use App\Infrastructure\Controllers\UserController;
use App\Infrastructure\Controllers\CandidateController;
use App\Infrastructure\Controllers\ConsumerController;
use App\Infrastructure\Controllers\PermissionsController;

use Illuminate\Support\Facades\Route;

//todo: Candidate!!!!
//todo: Falta crud permission y roles

Route::post('consumer/authenticate', [ ConsumerController::class, 'authenticate' ])
    ->middleware([ 'throttle:6,1' ]);

Route::prefix('v1')->middleware([ 'throttle:6,1', ConsumerAuthMiddleware::class, JwtAuthMiddleware::class])->group(function () {
    Route::post('user/authenticate', [ UserController::class, 'authenticate' ])->withoutMiddleware([ JwtAuthMiddleware::class]);

    Route::prefix('user')->group(function () {
        Route::get('', [ UserController::class, 'index' ]);//->middleware([ CheckPermissionMiddleware::class . ':index_users', CheckRoleMiddleware::class . ':admin' ]);
        Route::get('/{id}', [ UserController::class, 'show' ]);
        Route::post('', [ UserController::class, 'store' ])->withoutMiddleware([ JwtAuthMiddleware::class]);
        Route::put('', [ UserController::class, 'update' ]);
        Route::delete('', [ UserController::class, 'delete' ]);

        Route::post('{userId}/role/', [ UserController::class, 'assignRoleToUser' ])
            ->middleware([ CheckRoleMiddleware::class . ':admin' ]);//CheckPermissionMiddleware::class . ':assign_roles'

        Route::get('email/verify/{id}/{hash}', [ UserController::class, 'verifyEmail' ])
            ->name('email.verify')
            ->middleware([ 'signed' ])
            ->withoutMiddleware([ JwtAuthMiddleware::class, ConsumerAuthMiddleware::class]);
    });

    Route::prefix('candidate')->middleware([ CheckRoleMiddleware::class . ':candidate' ])->group(function () {
        Route::get('', [ CandidateController::class, 'index' ])
            ->middleware([ CheckPermissionMiddleware::class . ':index_candidates', CheckRoleMiddleware::class . ':admin' ])
            ->withoutMiddleware([ CheckRoleMiddleware::class . ':candidate' ]);

        Route::get('/{id}', [ CandidateController::class, 'show' ]);
        Route::post('', [ CandidateController::class, 'store' ])->withoutMiddleware([ JwtAuthMiddleware::class]);
        Route::put('', [ CandidateController::class, 'update' ]);
        Route::delete('', [ CandidateController::class, 'delete' ]);
    });

    Route::prefix('admin')->middleware([ CheckRoleMiddleware::class . ':admin' ])->group(function () {
        Route::prefix('role')->group(function () {
            Route::get('', [ RoleController::class, 'index' ]);//->middleware([ CheckPermissionMiddleware::class . ':index_roles' ]);
            Route::get('/{id}', [ RoleController::class, 'show' ]);//->middleware([ CheckPermissionMiddleware::class . ':index_roles' ]);
            Route::post('', [ RoleController::class, 'store' ]);//->middleware([ CheckPermissionMiddleware::class . ':create_roles' ]);
            Route::put('', [ RoleController::class, 'update' ]);//->middleware([ CheckPermissionMiddleware::class . ':update_roles' ]);
            Route::delete('', [ RoleController::class, 'delete' ]);//->middleware([ CheckPermissionMiddleware::class . ':delete_roles' ]);

            Route::post('{roleId}/permission', [ RoleController::class, 'assignPermissionToRole' ]);//->middleware([
            //     CheckPermissionMiddleware::class . ':create_permissions',
            //     CheckPermissionMiddleware::class . ':create_roles'
            // ]);
        });

        Route::prefix('permission')->group(function () {
            Route::get('', [ PermissionsController::class, 'index' ]);//->middleware([ CheckPermissionMiddleware::class . ':index_permissions' ]);
            Route::get('/{id}', [ PermissionsController::class, 'show' ]);//->middleware([ CheckPermissionMiddleware::class . ':index_permissions' ]);
            Route::post('', [ PermissionsController::class, 'store' ]);//->middleware([ CheckPermissionMiddleware::class . ':create_permissions' ]);
            Route::put('', [ PermissionsController::class, 'update' ]);//->middleware([ CheckPermissionMiddleware::class . ':update_permissions' ]);
            Route::delete('', [ PermissionsController::class, 'delete' ]);//->middleware([ CheckPermissionMiddleware::class . ':delete_permissions' ]);
        });
    });
});
