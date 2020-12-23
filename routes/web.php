<?php

use App\Http\Controllers\AddressController;
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
use App\Http\Controllers\ServiceReportController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Auth::routes([
    'register' => false,
]);

Route::get('/otp', [SecondFactorController::class, 'index'])->name('otp');
Route::post('/otp', [LoginController::class, 'loginSecondFactorOneTimePassword']);

Route::get('/reauthenticate', [ReauthenticateController::class.'index'])->name('reauthenticate');
Route::post('/reauthenticate', [ReauthenticateController::class, 'reauthenticate']);

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::resource('addresses', AddressController::class);
Route::resource('companies', CompanyController::class);
Route::resource('help', HelpController::class)->only(['index', 'show']);
Route::resource('memos', MemoController::class);
Route::resource('people', PersonController::class);
Route::resource('projects', ProjectController::class);

Route::resource('service-reports', ServiceReportController::class);
Route::get('/service-reports/{service_report}/email-download-request', [ServiceReportController::class, 'showEmailDownloadRequest'])->name('service-reports.email-download-request');
Route::post('/service-reports/{service_report}/email-download-request', [ServiceReportController::class, 'emailDownloadRequest']);
Route::get('/service-reports/{service_report}/email-signature-request', [ServiceReportController::class, 'showEmailSignatureRequest'])->name('service-reports.email-signature-request');
Route::post('/service-reports/{service_report}/email-signature-request', [ServiceReportController::class, 'emailSignatureRequest']);
Route::get('/service-reports/{service_report}/download', [ServiceReportController::class, 'download'])->name('service-reports.download');
Route::get('/service-reports/sign/{token}', [ServiceReportController::class, 'showSignRequest'])->name('service-reports.sign');
Route::post('/service-reports/sign/{token}', [ServiceReportController::class, 'sign']);
Route::get('/service-reports/download/{token}', [ServiceReportController::class, 'customerDownload'])->name('service-reports.customer-download');
Route::post('/service-reports/email-download-request/{token}', [ServiceReportController::class, 'customerEmailDownloadRequest'])->name('service-reports.customer-email-download-request');

Route::resource('tasks', TaskController::class);
Route::resource('comments', CommentController::class)->except(['index', 'show']);

Route::get('/mail', function () {
    return new App\Mail\ServiceReportSignatureRequestMail(\App\Models\ServiceReport::find(3)->load('project')->load('employee.person')->load('signatureRequest')->loadMin('services', 'provided_on')->loadMax('services', 'provided_on')->loadSum('services', 'hours')->loadSum('services', 'allowances')->loadSum('services', 'kilometres'));
});
