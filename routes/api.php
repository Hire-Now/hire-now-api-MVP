<?php

use App\Application\Middlewares\CheckPermissionMiddleware;
use App\Application\Middlewares\CheckRoleMiddleware;
use App\Application\Middlewares\JwtAuthMiddleware;
use App\Infrastructure\Controllers\RoleController;
use App\Infrastructure\Controllers\UserController;
use App\Infrastructure\Controllers\CandidateController;
use App\Infrastructure\Controllers\PermissionsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware([ 'throttle:6,1', JwtAuthMiddleware::class])->group(function () {
    Route::post('user/authenticate', [ UserController::class, 'authenticate' ])->withoutMiddleware([ JwtAuthMiddleware::class]);

    Route::resource('user', UserController::class)
        ->only([ 'index', 'show', 'store', 'update', 'destroy' ])
        ->middleware(CheckPermissionMiddleware::class . ':index_users');

    Route::get('user/email/verify/{id}/{hash}', [ UserController::class, 'verifyEmail' ])
        ->name('email.verify')
        ->middleware([ 'signed' ])
        ->withoutMiddleware([ JwtAuthMiddleware::class]);

    Route::resource('candidate', CandidateController::class)
        ->only([ 'index', 'show', 'store', 'update', 'destroy' ]);

    Route::prefix('admin')->middleware([ CheckRoleMiddleware::class . ':admin' ])->group(function () {
        Route::resource('role', RoleController::class)
            ->only([ 'index', 'show', 'store', 'update', 'destroy' ])
            ->middleware([
                'index'   => CheckPermissionMiddleware::class . ':index_roles',
                'show'    => CheckPermissionMiddleware::class . ':index_roles',
                'store'   => CheckPermissionMiddleware::class . ':create_roles',
                'update'  => CheckPermissionMiddleware::class . ':update_roles',
                'destroy' => CheckPermissionMiddleware::class . ':delete_roles',
            ]);

        Route::post('role/{roleId}/permission', [ RoleController::class, 'assignPermissionToRole' ])
            ->middleware([
                CheckPermissionMiddleware::class . ':create_permissions',
                CheckPermissionMiddleware::class . ':create_roles'
            ]);

        Route::resource('permission', PermissionsController::class)
            ->only([ 'index', 'show', 'store', 'update', 'destroy' ])
            ->middleware([
                'index'   => CheckPermissionMiddleware::class . ':index_permissions',
                'show'    => CheckPermissionMiddleware::class . ':index_permissions',
                'store'   => CheckPermissionMiddleware::class . ':create_permissions',
                'update'  => CheckPermissionMiddleware::class . ':update_permissions',
                'destroy' => CheckPermissionMiddleware::class . ':delete_permissions',
            ]);
    });
});
