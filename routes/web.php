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

use App\Http\Controllers\ApplicationSettingsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ReauthenticateController;
use App\Http\Controllers\Auth\SecondFactorController;
use App\Http\Controllers\ChangelogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemoController;
use App\Http\Controllers\OfflineController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\QrScanController;
use App\Http\Controllers\ServiceReportController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\WebpushController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Auth::routes([
    'register' => false,
]);

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/offline', [OfflineController::class, 'index'])->name('offline.index');

Route::middleware(['guest'])->group(function () {
    Route::get('/otp', [SecondFactorController::class, 'index'])->name('otp');
    Route::post('/otp', [LoginController::class, 'loginSecondFactorOneTimePassword']);

    Route::get('/service-reports/sign/{token}', [ServiceReportController::class, 'customerShowSignatureRequest'])->name('service-reports.customer-sign');
    Route::post('/service-reports/sign/{token}', [ServiceReportController::class, 'customerSign']);
    Route::get('/service-reports/download/{token}', [ServiceReportController::class, 'customerDownload'])->name('service-reports.customer-download');
    Route::post('/service-reports/email-download-request/{token}', [ServiceReportController::class, 'customerEmailDownloadRequest'])->name('service-reports.customer-email-download-request');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/reauthenticate', [ReauthenticateController::class.'index'])->name('reauthenticate');
    Route::post('/reauthenticate', [ReauthenticateController::class, 'reauthenticate']);

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/home', [HomeController::class, 'post']);

    Route::resource('addresses', AddressController::class);
    Route::resource('companies', CompanyController::class);

    Route::resource('employees', EmployeeController::class);
    Route::get('/employees/{employee}/access-grant', [EmployeeController::class, 'grantAccess'])->name('employees.access-grant');
    Route::get('/employees/{employee}/access-deny', [EmployeeController::class, 'denyAccess'])->name('employees.access-deny');

    Route::resource('help', HelpController::class)->only(['index', 'show']);
    Route::get('changelog', [ChangelogController::class, 'show'])->name('changelog.show');

    Route::resource('memos', MemoController::class);
    Route::get('/memos/{memo}/download', [MemoController::class, 'download'])->name('memos.download');
    Route::get('/memos/{memo}/email', [MemoController::class, 'showEmail'])->name('memos.email');
    Route::post('/memos/{memo}/email', [MemoController::class, 'email']);

    Route::resource('people', PersonController::class);
    Route::resource('projects', ProjectController::class);

    Route::resource('service-reports', ServiceReportController::class);
    Route::get('/service-reports/{service_report}/download', [ServiceReportController::class, 'download'])->name('service-reports.download');
    Route::get('/service-reports/{service_report}/email', [ServiceReportController::class, 'showEmail'])->name('service-reports.email');
    Route::post('/service-reports/{service_report}/email', [ServiceReportController::class, 'email']);
    Route::get('/service-reports/{service_report}/sign', [ServiceReportController::class, 'showSignatureRequest'])->name('service-reports.sign');
    Route::post('/service-reports/{service_report}/sign', [ServiceReportController::class, 'sign']);
    Route::get('/service-reports/{service_report}/email-download-request', [ServiceReportController::class, 'showEmailDownloadRequest'])->name('service-reports.email-download-request');
    Route::post('/service-reports/{service_report}/email-download-request', [ServiceReportController::class, 'emailDownloadRequest']);
    Route::get('/service-reports/{service_report}/email-signature-request', [ServiceReportController::class, 'showEmailSignatureRequest'])->name('service-reports.email-signature-request');
    Route::post('/service-reports/{service_report}/email-signature-request', [ServiceReportController::class, 'emailSignatureRequest']);

    Route::resource('tasks', TaskController::class);
    Route::get('/tasks/{task}/download', [TaskController::class, 'download'])->name('tasks.download');
    Route::get('/tasks/{task}/email', [TaskController::class, 'showEmail'])->name('tasks.email');
    Route::post('/tasks/{task}/email', [TaskController::class, 'email']);
    Route::resource('comments', CommentController::class)->except(['index', 'show']);

    Route::get('user-settings', [UserSettingsController::class, 'edit'])->name('user-settings.edit');
    Route::post('user-settings/signature', [UserSettingsController::class, 'updateSignature'])->name('user-settings.update-signature');

    Route::get('application-settings', [ApplicationSettingsController::class, 'edit'])->name('application-settings.edit');
    Route::post('application-settings/general', [ApplicationSettingsController::class, 'updateGeneral'])->name('application-settings.update-general');

    Route::get('/qr-scan', [QrScanController::class, 'index'])->name('qr-scan.index');
    Route::get('/storage/{file_path}', [StorageController::class, 'getFile'])->where(['file_path' => '.*'])->name('storage.get-file');
    Route::post('/webpush', [WebpushController::class, 'store'])->name('webpush.store');
    Route::delete('/webpush', [WebpushController::class, 'destroy'])->name('webpush.destroy');
    Route::get('/webpush/test', [WebpushController::class, 'test'])->name('webpush.test');
});
