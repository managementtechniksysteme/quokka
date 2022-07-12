<?php

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

use App\Http\Controllers\AccountingController;
use App\Http\Controllers\AdditionsReportController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ApplicationSettingsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ReauthenticateController;
use App\Http\Controllers\Auth\SecondFactorController;
use App\Http\Controllers\ChangelogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ConstructionReportController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExceptionController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InspectionReportController;
use App\Http\Controllers\InterimInvoiceController;
use App\Http\Controllers\LatestChangesController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\MaterialServiceController;
use App\Http\Controllers\MemoController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OfflineController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\QrScanController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SentEmailController;
use App\Http\Controllers\ServiceReportController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\WageServiceController;
use App\Http\Controllers\WebpushController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Auth::routes([
    'register' => false,
]);

Route::get('/offline', [OfflineController::class, 'index'])->name('offline.index');

Route::middleware(['guest'])->group(function () {
    Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

    Route::get('/otp', [SecondFactorController::class, 'index'])->name('otp');
    Route::post('/otp', [LoginController::class, 'loginSecondFactorOneTimePassword']);

    Route::get('/additions-reports/sign/{token}', [AdditionsReportController::class, 'customerShowSignatureRequest'])->name('additions-reports.customer-sign');
    Route::post('/additions-reports/sign/{token}', [AdditionsReportController::class, 'customerSign']);
    Route::get('/additions-reports/download/{token}', [AdditionsReportController::class, 'customerDownload'])->name('additions-reports.customer-download');
    Route::post('/additions-reports/email-download-request/{token}', [AdditionsReportController::class, 'customerEmailDownloadRequest'])->name('additions-reports.customer-email-download-request');

    Route::get('/construction-reports/sign/{token}', [ConstructionReportController::class, 'customerShowSignatureRequest'])->name('construction-reports.customer-sign');
    Route::post('/construction-reports/sign/{token}', [ConstructionReportController::class, 'customerSign']);
    Route::get('/construction-reports/download/{token}', [ConstructionReportController::class, 'customerDownload'])->name('construction-reports.customer-download');
    Route::post('/construction-reports/email-download-request/{token}', [ConstructionReportController::class, 'customerEmailDownloadRequest'])->name('construction-reports.customer-email-download-request');

    Route::get('/inspection-reports/sign/{token}', [InspectionReportController::class, 'customerShowSignatureRequest'])->name('inspection-reports.customer-sign');
    Route::post('/inspection-reports/sign/{token}', [InspectionReportController::class, 'customerSign']);
    Route::get('/inspection-reports/download/{token}', [InspectionReportController::class, 'customerDownload'])->name('inspection-reports.customer-download');
    Route::post('/inspection-reports/email-download-request/{token}', [InspectionReportController::class, 'customerEmailDownloadRequest'])->name('inspection-reports.customer-email-download-request');

    Route::get('/service-reports/sign/{token}', [ServiceReportController::class, 'customerShowSignatureRequest'])->name('service-reports.customer-sign');
    Route::post('/service-reports/sign/{token}', [ServiceReportController::class, 'customerSign']);
    Route::get('/service-reports/download/{token}', [ServiceReportController::class, 'customerDownload'])->name('service-reports.customer-download');
    Route::post('/service-reports/email-download-request/{token}', [ServiceReportController::class, 'customerEmailDownloadRequest'])->name('service-reports.customer-email-download-request');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/reauthenticate', [ReauthenticateController::class, 'index'])->name('reauthenticate');
    Route::post('/reauthenticate', [ReauthenticateController::class, 'reauthenticate']);

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/home', [HomeController::class, 'post']);

    Route::resource('accounting', AccountingController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('/accounting/download', [AccountingController::class, 'download'])->name('accounting.download');

    Route::resource('additions-reports', AdditionsReportController::class);
    Route::get('/additions-reports/{additions_report}/download', [AdditionsReportController::class, 'download'])->name('additions-reports.download');
    Route::get('/additions-reports/{additions_report}/email', [AdditionsReportController::class, 'showEmail'])->name('additions-reports.email');
    Route::post('/additions-reports/{additions_report}/email', [AdditionsReportController::class, 'email']);
    Route::get('/additions-reports/{additions_report}/sign', [AdditionsReportController::class, 'showSignatureRequest'])->name('additions-reports.sign');
    Route::post('/additions-reports/{additions_report}/sign', [AdditionsReportController::class, 'sign']);
    Route::get('/additions-reports/{additions_report}/email-download-request', [AdditionsReportController::class, 'showEmailDownloadRequest'])->name('additions-reports.email-download-request');
    Route::post('/additions-reports/{additions_report}/email-download-request', [AdditionsReportController::class, 'emailDownloadRequest']);
    Route::get('/additions-reports/{additions_report}/email-signature-request', [AdditionsReportController::class, 'showEmailSignatureRequest'])->name('additions-reports.email-signature-request');
    Route::post('/additions-reports/{additions_report}/email-signature-request', [AdditionsReportController::class, 'emailSignatureRequest']);
    Route::get('/additions-reports/{additions_report}/finish', [AdditionsReportController::class, 'finish'])->name('additions-reports.finish');

    Route::resource('addresses', AddressController::class);
    Route::resource('companies', CompanyController::class);

    Route::resource('construction-reports', ConstructionReportController::class);
    Route::get('/construction-reports/{construction_report}/download', [ConstructionReportController::class, 'download'])->name('construction-reports.download');
    Route::get('/construction-reports/{construction_report}/email', [ConstructionReportController::class, 'showEmail'])->name('construction-reports.email');
    Route::post('/construction-reports/{construction_report}/email', [ConstructionReportController::class, 'email']);
    Route::get('/construction-reports/{construction_report}/sign', [ConstructionReportController::class, 'showSignatureRequest'])->name('construction-reports.sign');
    Route::post('/construction-reports/{construction_report}/sign', [ConstructionReportController::class, 'sign']);
    Route::get('/construction-reports/{construction_report}/email-download-request', [ConstructionReportController::class, 'showEmailDownloadRequest'])->name('construction-reports.email-download-request');
    Route::post('/construction-reports/{construction_report}/email-download-request', [ConstructionReportController::class, 'emailDownloadRequest']);
    Route::get('/construction-reports/{construction_report}/email-signature-request', [ConstructionReportController::class, 'showEmailSignatureRequest'])->name('construction-reports.email-signature-request');
    Route::post('/construction-reports/{construction_report}/email-signature-request', [ConstructionReportController::class, 'emailSignatureRequest']);
    Route::get('/construction-reports/{construction_report}/finish', [ConstructionReportController::class, 'finish'])->name('construction-reports.finish');

    Route::resource('employees', EmployeeController::class);
    Route::get('/employees/{employee}/access-grant', [EmployeeController::class, 'grantAccess'])->name('employees.access-grant');
    Route::get('/employees/{employee}/access-deny', [EmployeeController::class, 'denyAccess'])->name('employees.access-deny');
    Route::get('/employees/{employee}/edit-permissions', [EmployeeController::class, 'editPermissions'])->name('employees.edit-permissions');
    Route::patch('/employees/{employee}/edit-permissions', [EmployeeController::class, 'updatePermissions'])->name('employees.update-permissions');
    Route::get('/employees/{employee}/impersonate', [EmployeeController::class, 'impersonate'])->name('employees.impersonate');

    Route::resource('sent-emails', SentEmailController::class)->only(['index']);

    Route::resource('exceptions', ExceptionController::class)->only(['index', 'show', 'destroy']);

    Route::resource('help', HelpController::class)->only(['index', 'show']);
    Route::get('changelog', [ChangelogController::class, 'show'])->name('changelog.show');

    Route::resource('inspection-reports', InspectionReportController::class);
    Route::get('/inspection-reports/{inspection_report}/download', [InspectionReportController::class, 'download'])->name('inspection-reports.download');
    Route::get('/inspection-reports/{inspection_report}/email', [InspectionReportController::class, 'showEmail'])->name('inspection-reports.email');
    Route::post('/inspection-reports/{inspection_report}/email', [InspectionReportController::class, 'email']);
    Route::get('/inspection-reports/{inspection_report}/sign', [InspectionReportController::class, 'showSignatureRequest'])->name('inspection-reports.sign');
    Route::post('/inspection-reports/{inspection_report}/sign', [InspectionReportController::class, 'sign']);
    Route::get('/inspection-reports/{inspection_report}/email-download-request', [InspectionReportController::class, 'showEmailDownloadRequest'])->name('inspection-reports.email-download-request');
    Route::post('/inspection-reports/{inspection_report}/email-download-request', [InspectionReportController::class, 'emailDownloadRequest']);
    Route::get('/inspection-reports/{inspection_report}/email-signature-request', [InspectionReportController::class, 'showEmailSignatureRequest'])->name('inspection-reports.email-signature-request');
    Route::post('/inspection-reports/{inspection_report}/email-signature-request', [InspectionReportController::class, 'emailSignatureRequest']);
    Route::get('/inspection-reports/{inspection_report}/finish', [InspectionReportController::class, 'finish'])->name('inspection-reports.finish');

    Route::resource('latest-changes', LatestChangesController::class)->only(['index']);

    Route::resource('logbook', LogbookController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('/logbook/download', [LogbookController::class, 'download'])->name('logbook.download');

    Route::resource('material-services', MaterialServiceController::class);

    Route::resource('memos', MemoController::class);
    Route::get('/memos/{memo}/download', [MemoController::class, 'download'])->name('memos.download');
    Route::get('/memos/{memo}/email', [MemoController::class, 'showEmail'])->name('memos.email');
    Route::post('/memos/{memo}/email', [MemoController::class, 'email']);

    Route::resource('notifications', NotificationController::class)->only(['index', 'destroy']);
    Route::post('/notifications', [NotificationController::class, 'clear'])->name('notifications.clear');

    Route::resource('people', PersonController::class);

    Route::get('/projects/list', [ProjectController::class, 'downloadList'])->name('projects.download-list');
    Route::resource('projects', ProjectController::class);
    Route::get('/projects/{project}/download', [ProjectController::class, 'showDownload'])->name('projects.download');
    Route::post('/projects/{project}/download', [ProjectController::class, 'download']);

    Route::resource('/projects/{project}/interim-invoices', InterimInvoiceController::class)->except(['index']);

    Route::resource('roles', RoleController::class);

    Route::resource('search', SearchController::class)->only(['index']);

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
    Route::get('/service-reports/{service_report}/finish', [ServiceReportController::class, 'finish'])->name('service-reports.finish');

    Route::get('/tasks/list', [TaskController::class, 'downloadList'])->name('tasks.download-list');
    Route::resource('tasks', TaskController::class);
    Route::get('/tasks/{task}/download', [TaskController::class, 'download'])->name('tasks.download');
    Route::get('/tasks/{task}/email', [TaskController::class, 'showEmail'])->name('tasks.email');
    Route::post('/tasks/{task}/email', [TaskController::class, 'email']);
    Route::get('/tasks/{task}/finish', [TaskController::class, 'finish'])->name('tasks.finish');
    Route::resource('comments', CommentController::class)->except(['index', 'show']);

    Route::get('user-settings', [UserSettingsController::class, 'edit'])->name('user-settings.edit');
    Route::post('user-settings/interface', [UserSettingsController::class, 'updateInterface'])->name('user-settings.update-interface');
    Route::post('user-settings/notifications', [UserSettingsController::class, 'updateNotifications'])->name('user-settings.update-notifications');
    Route::post('user-settings/notification-targets', [UserSettingsController::class, 'updateNotificationTargets'])->name('user-settings.update-notification-targets');
    Route::post('user-settings/signature', [UserSettingsController::class, 'updateSignature'])->name('user-settings.update-signature');
    Route::post('user-settings/password', [UserSettingsController::class, 'updatePassword'])->name('user-settings.update-password');
    Route::post('user-settings/otp-enable', [UserSettingsController::class, 'enableOtp'])->name('user-settings.otp-enable');
    Route::post('user-settings/otp-confirm', [UserSettingsController::class, 'confirmOtp'])->name('user-settings.otp-confirm');
    Route::post('user-settings/otp-disable', [UserSettingsController::class, 'disableOtp'])->name('user-settings.otp-disable');

    Route::get('application-settings', [ApplicationSettingsController::class, 'edit'])->name('application-settings.edit');
    Route::post('application-settings/general', [ApplicationSettingsController::class, 'updateGeneral'])->name('application-settings.update-general');

    Route::get('/qr-scan', [QrScanController::class, 'index'])->name('qr-scan.index');
    Route::get('/storage/{file_path}', [StorageController::class, 'getFile'])->where(['file_path' => '.*'])->name('storage.get-file');

    Route::resource('vehicles', VehicleController::class);

    Route::resource('wage-services', WageServiceController::class);

    Route::post('/webpush', [WebpushController::class, 'store'])->name('webpush.store');
    Route::delete('/webpush', [WebpushController::class, 'destroy'])->name('webpush.destroy');
    Route::get('/webpush/test', [WebpushController::class, 'test'])->name('webpush.test');
});
