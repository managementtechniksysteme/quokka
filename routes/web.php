<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ReauthenticateController;
use App\Http\Controllers\Auth\SecondFactorController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemoController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WelcomeController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Auth::routes([
    'register' => false,
]);

Route::get('/otp', [SecondFactorController::class, 'index'])->name('otp');
Route::post('/otp', [LoginController::class, 'loginSecondFactorOneTimePassword']);

Route::get('/reauthenticate', [ReauthenticateController::class. 'index'])->name('reauthenticate');
Route::post('/reauthenticate', [ReauthenticateController::class, 'reauthenticate']);

Route::get('/home', [HomeController::class. 'index'])->name('home');

Route::resource('addresses', AddressController::class);
Route::resource('companies', CompanyController::class);
Route::resource('help', HelpController::class)->only(['index', 'show']);
Route::resource('memos', MemoController::class);
Route::resource('people', PersonController::class);
Route::resource('projects', ProjectController::class);
Route::resource('tasks', TaskController::class);
Route::resource('comments', CommentController::class)->except(['index', 'show']);
