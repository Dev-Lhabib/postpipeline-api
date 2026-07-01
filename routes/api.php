<?php
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BlueprintController;
use App\Http\Controllers\Api\ContentController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/blueprints', [BlueprintController::class, 'index']);
    Route::get('/blueprints/{blueprint}', [BlueprintController::class, 'show']);
    Route::post('/blueprints', [BlueprintController::class, 'store']);
    Route::post('/content/repurpose', [ContentController::class, 'repurpose']);

});