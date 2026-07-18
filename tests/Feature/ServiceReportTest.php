<?php

namespace Tests\Feature;

use App\Events\ServiceReportCreatedEvent;
use App\Events\ServiceReportSignedEvent;
use App\Events\ServiceReportUpdatedEvent;
use App\Mail\ServiceReportDownloadRequestMail;
use App\Mail\ServiceReportMail;
use App\Mail\ServiceReportSignatureRequestMail;
use App\Models\Project;
use App\Models\ServiceReport;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

const TINY_PNG_BASE64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';

function serviceReportUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

function ownServiceReport(User $user, array $attributes = []): ServiceReport
{
    return ServiceReport::factory()->withServices()->create(array_merge(['employee_id' => $user->employee_id], $attributes));
}

// index

test('index is shown for a user with view permission', function () {
    $user = serviceReportUser(['service-reports.view.own']);

    $response = $this->actingAs($user)->get(route('service-reports.index'));

    $response->assertSuccessful();
    $response->assertViewIs('service_report.index');
});

test('status-asc and status-desc sort reports new, signed, then finished (and reverse)', function () {
    $user = serviceReportUser(['service-reports.view.own']);
    ownServiceReport($user, ['status' => 'finished']);
    ownServiceReport($user, ['status' => 'new']);
    ownServiceReport($user, ['status' => 'signed']);

    $ascending = $this->actingAs($user)->get(route('service-reports.index', ['search' => '', 'sort' => 'status-asc']));
    $descending = $this->actingAs($user)->get(route('service-reports.index', ['search' => '', 'sort' => 'status-desc']));

    expect($ascending->viewData('serviceReports')->pluck('status')->all())->toBe(['new', 'signed', 'finished']);
    expect($descending->viewData('serviceReports')->pluck('status')->all())->toBe(['finished', 'signed', 'new']);
});

test('index is forbidden without view permission', function () {
    $user = serviceReportUser();

    $response = $this->actingAs($user)->get(route('service-reports.index'));

    $response->assertForbidden();
});

// create / store

test('create form is shown for a user with create permission', function () {
    $user = serviceReportUser(['service-reports.create']);

    $response = $this->actingAs($user)->get(route('service-reports.create'));

    $response->assertSuccessful();
    $response->assertViewIs('service_report.create');
});

test('create form prefills from accounting entries', function () {
    $user = serviceReportUser(['service-reports.create']);
    $wageService = \App\Models\WageService::factory()->create(['unit' => 'h']);
    $project = Project::factory()->create();
    $accounting = \App\Models\Accounting::factory()->create([
        'employee_id' => $user->employee_id,
        'service_id' => $wageService->id,
        'project_id' => $project->id,
        'comment' => 'first',
    ]);

    $response = $this->actingAs($user)->get(route('service-reports.create', ['accounting' => [$accounting->id]]));

    $response->assertSuccessful();
    $response->assertViewHas('currentProject', fn ($currentProject) => $currentProject->is($project));
});

test('create form prefills from logbook entries', function () {
    $user = serviceReportUser(['service-reports.create']);
    $project = Project::factory()->create();
    $logbook = \App\Models\Logbook::factory()->create([
        'employee_id' => $user->employee_id,
        'project_id' => $project->id,
    ]);

    $response = $this->actingAs($user)->get(route('service-reports.create', ['logbook' => [$logbook->id]]));

    $response->assertSuccessful();
    $response->assertViewHas('currentProject', fn ($currentProject) => $currentProject->is($project));
});

test('store creates a service report for the authenticated employee', function () {
    Event::fake([ServiceReportCreatedEvent::class]);
    $user = serviceReportUser(['service-reports.create']);
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->post(route('service-reports.store'), [
        'project_id' => $project->id,
        'comment' => 'Test comment',
        'services' => [
            ['provided_on' => '2026-01-05', 'hours' => 2.5, 'kilometres' => 10],
        ],
    ]);

    $serviceReport = ServiceReport::sole();

    $response->assertRedirect(route('service-reports.show', $serviceReport));
    expect($serviceReport->employee_id)->toBe($user->employee_id);
    expect($serviceReport->status)->toBe('new');
    expect($serviceReport->services()->count())->toBe(1);
    Event::assertDispatched(ServiceReportCreatedEvent::class);
});

test('store is forbidden without create permission', function () {
    $user = serviceReportUser();
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->post(route('service-reports.store'), [
        'project_id' => $project->id,
        'comment' => 'Test comment',
        'services' => [
            ['provided_on' => '2026-01-05', 'hours' => 2.5, 'kilometres' => 10],
        ],
    ]);

    $response->assertForbidden();
    expect(ServiceReport::count())->toBe(0);
});

// show

test('show is allowed for own report with view.own permission', function () {
    $user = serviceReportUser(['service-reports.view.own']);
    $serviceReport = ownServiceReport($user);

    $response = $this->actingAs($user)->get(route('service-reports.show', $serviceReport));

    $response->assertSuccessful();
    $response->assertViewIs('service_report.show');
});

test('show is forbidden for other report without view.other permission', function () {
    $user = serviceReportUser(['service-reports.view.own']);
    $serviceReport = ServiceReport::factory()->create();

    $response = $this->actingAs($user)->get(route('service-reports.show', $serviceReport));

    $response->assertForbidden();
});

// edit

test('edit is shown for own report with update.own permission', function () {
    $user = serviceReportUser(['service-reports.update.own']);
    $serviceReport = ownServiceReport($user);

    $response = $this->actingAs($user)->get(route('service-reports.edit', $serviceReport));

    $response->assertSuccessful();
    $response->assertViewIs('service_report.edit');
});

// update

test('update persists changes to a non-finished report', function () {
    Event::fake([ServiceReportUpdatedEvent::class]);
    $user = serviceReportUser(['service-reports.update.own']);
    $serviceReport = ownServiceReport($user, ['status' => 'new']);
    $project = $serviceReport->project;

    $response = $this->actingAs($user)->put(route('service-reports.update', $serviceReport), [
        'project_id' => $project->id,
        'comment' => 'Updated comment',
        'services' => [
            ['provided_on' => '2026-02-01', 'hours' => 4, 'kilometres' => 20],
        ],
    ]);

    $response->assertRedirect(route('service-reports.show', $serviceReport));
    expect($serviceReport->fresh()->comment)->toBe('Updated comment');
    Event::assertDispatched(ServiceReportUpdatedEvent::class);
});

test('update is forbidden on a finished report', function () {
    $user = serviceReportUser(['service-reports.update.own']);
    $serviceReport = ownServiceReport($user, ['status' => 'finished']);
    $project = $serviceReport->project;

    $response = $this->actingAs($user)->put(route('service-reports.update', $serviceReport), [
        'project_id' => $project->id,
        'comment' => 'Updated comment',
        'services' => [
            ['provided_on' => '2026-02-01', 'hours' => 4, 'kilometres' => 20],
        ],
    ]);

    $response->assertForbidden();
});

test('updating a signed report reverts its status to new', function () {
    $user = serviceReportUser(['service-reports.update.own']);
    $serviceReport = ownServiceReport($user, ['status' => 'signed']);
    $project = $serviceReport->project;

    $this->actingAs($user)->put(route('service-reports.update', $serviceReport), [
        'project_id' => $project->id,
        'comment' => 'Updated comment',
        'services' => [
            ['provided_on' => '2026-02-01', 'hours' => 4, 'kilometres' => 20],
        ],
    ]);

    expect($serviceReport->fresh()->status)->toBe('new');
});

// destroy

test('destroy removes a non-finished own report', function () {
    $user = serviceReportUser(['service-reports.delete.own']);
    $serviceReport = ownServiceReport($user, ['status' => 'new']);

    $response = $this->actingAs($user)->delete(route('service-reports.destroy', $serviceReport));

    $response->assertRedirect(route('service-reports.index'));
    expect(ServiceReport::find($serviceReport->id))->toBeNull();
});

// sign

test('sign is allowed on a new report and stores a signature', function () {
    Storage::fake('local');
    Event::fake([ServiceReportSignedEvent::class]);
    $user = serviceReportUser(['service-reports.get-signature.own']);
    $serviceReport = ownServiceReport($user, ['status' => 'new']);

    $response = $this->actingAs($user)->post('/service-reports/'.$serviceReport->id.'/sign', [
        'signature' => TINY_PNG_BASE64,
    ]);

    expect($serviceReport->fresh()->status)->toBe('signed');
    Event::assertDispatched(ServiceReportSignedEvent::class);
});

test('sign is forbidden on an already signed report', function () {
    $user = serviceReportUser(['service-reports.get-signature.own']);
    $serviceReport = ownServiceReport($user, ['status' => 'signed']);

    $response = $this->actingAs($user)->post('/service-reports/'.$serviceReport->id.'/sign', [
        'signature' => TINY_PNG_BASE64,
    ]);

    $response->assertForbidden();
});

// finish

test('finish is allowed with approve permission', function () {
    $user = serviceReportUser(['service-reports.approve']);
    $serviceReport = ownServiceReport($user, ['status' => 'signed']);

    $response = $this->actingAs($user)->get(route('service-reports.finish', $serviceReport));

    $response->assertRedirect();
    expect($serviceReport->fresh()->status)->toBe('finished');
});

test('finish is forbidden without approve permission', function () {
    $user = serviceReportUser(['service-reports.update.own', 'service-reports.delete.own']);
    $serviceReport = ownServiceReport($user, ['status' => 'signed']);

    $response = $this->actingAs($user)->get(route('service-reports.finish', $serviceReport));

    $response->assertForbidden();
    expect($serviceReport->fresh()->status)->toBe('signed');
});

// activity log regression (spatie/laravel-activitylog v4 -> v5: attribute_changes column, not properties)

test('finishing a report writes an activity log entry with the new attribute_changes shape', function () {
    $user = serviceReportUser(['service-reports.approve']);
    $serviceReport = ownServiceReport($user, ['status' => 'signed']);

    $this->actingAs($user)->get(route('service-reports.finish', $serviceReport));

    $activity = Activity::where('subject_type', ServiceReport::class)
        ->where('subject_id', $serviceReport->id)
        ->latest('id')
        ->first();

    expect($activity)->not->toBeNull();
    expect($activity->attribute_changes['attributes']['status'] ?? null)->toBe('finished');
    expect($activity->attribute_changes['old']['status'] ?? null)->toBe('signed');
});

// email

test('email sends the service report mail', function () {
    Mail::fake();
    $user = serviceReportUser(['service-reports.email.own']);
    $serviceReport = ownServiceReport($user);

    $response = $this->actingAs($user)->post(route('service-reports.email', $serviceReport), [
        'email_to' => [['email' => 'customer@example.com']],
    ]);

    // ServiceReportMail implements ShouldQueue, so Mail::fake() buckets it under queued, not sent.
    Mail::assertQueued(ServiceReportMail::class);
});

// email signature request

test('emailSignatureRequest is allowed on a new report and sends the mail', function () {
    Mail::fake();
    $user = serviceReportUser(['service-reports.send-signature-request.own']);
    $serviceReport = ownServiceReport($user, ['status' => 'new']);

    $response = $this->actingAs($user)->post('/service-reports/'.$serviceReport->id.'/email-signature-request', [
        'email' => 'customer@example.com',
    ]);

    Mail::assertQueued(ServiceReportSignatureRequestMail::class);
    expect($serviceReport->fresh()->signatureRequest)->not->toBeNull();
});

// email download request

test('emailDownloadRequest is allowed on a signed report and sends the mail', function () {
    Mail::fake();
    $user = serviceReportUser(['service-reports.send-download-request.own']);
    $serviceReport = ownServiceReport($user, ['status' => 'signed']);

    $response = $this->actingAs($user)->post('/service-reports/'.$serviceReport->id.'/email-download-request', [
        'email' => 'customer@example.com',
    ]);

    Mail::assertQueued(ServiceReportDownloadRequestMail::class);
});

// download (real pdflatex, no mocking - the container has the binary installed)

test('download renders a real pdf for an authorized user', function () {
    $user = serviceReportUser(['service-reports.createpdf.own']);
    $serviceReport = ownServiceReport($user);

    $response = $this->actingAs($user)->get(route('service-reports.download', $serviceReport));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'application/pdf');
})->group('pdflatex');

test('downloadList renders a real pdf for an authorized user', function () {
    $user = serviceReportUser(['service-reports.view.own']);
    ownServiceReport($user);

    $response = $this->actingAs($user)->get(route('service-reports.download-list'));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'application/pdf');
})->group('pdflatex');

test('downloadList is forbidden without view permission', function () {
    $user = serviceReportUser();

    $response = $this->actingAs($user)->get(route('service-reports.download-list'));

    $response->assertForbidden();
});

// checkOverlap

test('checkOverlap reports existing service reports covering the given dates', function () {
    $user = serviceReportUser(['service-reports.view.own']);
    $serviceReport = ownServiceReport($user, ['status' => 'new']);
    $serviceReport->services()->create([
        'provided_on' => '2026-03-10',
        'hours' => 4,
        'kilometres' => 5,
    ]);

    $response = $this->actingAs($user)->get(route('service-reports.check-overlap', [
        'project_id' => $serviceReport->project_id,
        'dates' => ['2026-03-10'],
    ]));

    $response->assertSuccessful();
    $response->assertJsonCount(1, 'reports');
});

test('checkOverlap is forbidden without create permission', function () {
    $user = serviceReportUser();
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->get(route('service-reports.check-overlap', [
        'project_id' => $project->id,
        'dates' => ['2026-03-10'],
    ]));

    $response->assertForbidden();
});

// customer-facing signed routes (token-gated, no auth)

test('customer sign form is shown for a valid signature request token', function () {
    $serviceReport = ownServiceReport(serviceReportUser(), ['status' => 'new']);
    $serviceReport->generateSignatureRequest();

    $response = $this->get(route('service-reports.customer-sign', $serviceReport->fresh()->signatureRequest->token));

    $response->assertSuccessful();
    $response->assertViewIs('service_report.show_customer_signature_request');
    $response->assertViewHas('serviceReport', fn ($viewServiceReport) => $viewServiceReport->id === $serviceReport->id);
});

test('customer sign form warns on an invalid token', function () {
    $response = $this->get(route('service-reports.customer-sign', 'not-a-real-token'));

    $response->assertSuccessful();
    $response->assertViewHas('serviceReport', null);
    expect(session('warning'))->not->toBeNull();
});

test('customer sign stores a signature and generates a download request', function () {
    Storage::fake('local');
    Event::fake([ServiceReportSignedEvent::class]);
    $serviceReport = ownServiceReport(serviceReportUser(), ['status' => 'new']);
    $serviceReport->generateSignatureRequest();
    $token = $serviceReport->fresh()->signatureRequest->token;

    $response = $this->post(route('service-reports.customer-sign', $token), [
        'signature' => TINY_PNG_BASE64,
    ]);

    $response->assertSuccessful();
    expect($serviceReport->fresh()->status)->toBe('signed');
    expect($serviceReport->fresh()->downloadRequest)->not->toBeNull();
    Event::assertDispatched(ServiceReportSignedEvent::class);
});

test('customer download deletes the download request and streams a real pdf', function () {
    $serviceReport = ownServiceReport(serviceReportUser(), ['status' => 'signed']);
    $serviceReport->generateDownloadRequest();
    $token = $serviceReport->fresh()->downloadRequest->token;

    $response = $this->get(route('service-reports.customer-download', $token));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'application/pdf');
    expect($serviceReport->fresh()->downloadRequest)->toBeNull();
})->group('pdflatex');

test('customer download warns on an invalid token instead of erroring', function () {
    $response = $this->get(route('service-reports.customer-download', 'not-a-real-token'));

    $response->assertSuccessful();
    $response->assertViewIs('service_report.download_invalid');
    expect(session('warning'))->not->toBeNull();
});

test('customer email download request queues the mail for a valid token', function () {
    Mail::fake();
    $serviceReport = ownServiceReport(serviceReportUser(), ['status' => 'signed']);
    $serviceReport->generateDownloadRequest();
    $token = $serviceReport->fresh()->downloadRequest->token;

    $response = $this->post(route('service-reports.customer-email-download-request', $token), [
        'email' => 'customer@example.com',
    ]);

    $response->assertSuccessful();
    Mail::assertQueued(ServiceReportDownloadRequestMail::class);
});

test('customer email download request warns on an invalid token instead of erroring', function () {
    Mail::fake();

    $response = $this->post(route('service-reports.customer-email-download-request', 'not-a-real-token'), [
        'email' => 'customer@example.com',
    ]);

    $response->assertSuccessful();
    $response->assertViewIs('service_report.download_invalid');
    expect(session('warning'))->not->toBeNull();
    Mail::assertNothingQueued();
});
