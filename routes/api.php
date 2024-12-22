<?php

use App\Infrastructure\Controllers\CandidateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/candidate', [CandidateController::class, 'store']);
Route::put('/candidate/{id}', [CandidateController::class, 'update']);
