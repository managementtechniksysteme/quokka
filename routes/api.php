<?php

use App\Http\Controllers\Api\AccountingController;
use App\Http\Controllers\Api\ApplicationSettingsController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\TokenController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\LogbookController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VehicleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('guest')->name('api.')->group(function () {
    Route::post('/token', [TokenController::class, 'token'])->name('token');
    Route::post('/otp', [TokenController::class, 'tokenSecondFactorOneTimePassword'])->name('token.otp');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forgot-password');
});

Route::middleware(['auth:sanctum', 'ability:refresh'])->name('api.')->group(function () {
    Route::post('/token/refresh', [TokenController::class, 'refreshToken'])->name('token.refresh');
});

Route::middleware(['auth:sanctum', 'ability:authenticate'])->name('api.')->group(function () {
    Route::apiResource('accounting', AccountingController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

    Route::apiResource('application-settings', ApplicationSettingsController::class)->only(['index',]);

    Route::apiResource('dashboard', DashboardController::class)->only(['index']);

    Route::get('/employees/select-options', [EmployeeController::class, 'selectOptions']);
    Route::apiResource('employees', EmployeeController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::get('/logbook/location-select-options', [LogbookController::class, 'locationSelectOptions']);
    Route::apiResource('logbook', LogbookController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

    Route::get('/projects/select-options', [ProjectController::class, 'selectOptions']);
    Route::apiResource('projects', ProjectController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::get('/services/hourly-based-ids', [ServiceController::class, 'hourlyBasedIds']);
    Route::get('/services/select-options', [ServiceController::class, 'selectOptions']);
    Route::get('/services/types', [ServiceController::class, 'types']);
    Route::apiResource('services', ServiceController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::get('/user', [UserController::class, 'index']);

    Route::get('/vehicles/current-kilometres', [VehicleController::class, 'currentKilometres']);
    Route::get('/vehicles/select-options', [VehicleController::class, 'selectOptions']);
    Route::apiResource('vehicles', VehicleController::class)->only(['index', 'store', 'update', 'destroy']);
});
