<?php


use App\Http\Api\V1\LinkController;
use Illuminate\Support\Facades\Route;

Route::prefix('links')->group(function () {
    Route::post('', [LinkController::class, 'create']);
    Route::get('', [LinkController::class, 'index']);
    Route::get('/list', [LinkController::class, 'list']);
    Route::get('/search', [LinkController::class, 'search']);
    Route::get('/{short_link}', [LinkController::class, 'click']);
});
