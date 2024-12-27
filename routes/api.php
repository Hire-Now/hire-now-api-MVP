<?php

use App\Infrastructure\Controllers\CandidateController;
use App\Infrastructure\Controllers\UserController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

// use Illuminate\Support\Facades\DB;

// DB::listen(function ($query) {
//     echo "<pre>{$query->sql}</pre>";

//     if (count($query->bindings) > 0) {
//         echo "<pre>Bindings: " . implode(', ', $query->bindings) . "</pre>";
//     }

//     echo "<pre>Time: {$query->time} ms</pre>";
// });

//todo: middleware que valide que el email ya se encuentra verificado
Route::prefix('v1')->group(function () {
    Route::group([ 'prefix' => 'user' ], function () {
        Route::post('/', [ UserController::class, 'store' ])->middleware([ 'throttle:6,1' ]);
        Route::get('/email/verify/{id}/{hash}', [ UserController::class, 'verifyEmail' ])
            ->name('email.verify')
            ->middleware([ 'signed' ]);
    });
})->middleware([ 'throttle:6,1' ]);
