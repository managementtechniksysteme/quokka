<?php

namespace Tests\Feature;

use App\Events\AdditionsReportCreatedEvent;
use App\Events\AdditionsReportSignedEvent;
use App\Events\AdditionsReportUpdatedEvent;
use App\Mail\AdditionsReportDownloadRequestMail;
use App\Mail\AdditionsReportMail;
use App\Mail\AdditionsReportSignatureRequestMail;
use App\Models\AdditionsReport;
use App\Models\Employee;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

const ADDITIONS_TINY_PNG_BASE64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';

function additionsReportUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

function ownAdditionsReport(User $user, array $attributes = []): AdditionsReport
{
    return AdditionsReport::factory()->withInvolvedEmployees()->create(array_merge(['employee_id' => $user->employee_id], $attributes));
}

// index

test('index is shown for a user with view permission', function () {
    $user = additionsReportUser(['additions-reports.view.own']);

    $response = $this->actingAs($user)->get(route('additions-reports.index'));

    $response->assertSuccessful();
    $response->assertViewIs('additions_report.index');
});

test('index is forbidden without view permission', function () {
    $user = additionsReportUser();

    $response = $this->actingAs($user)->get(route('additions-reports.index'));

    $response->assertForbidden();
});

// create / store

test('create form is shown for a user with create permission', function () {
    $user = additionsReportUser(['additions-reports.create']);

    $response = $this->actingAs($user)->get(route('additions-reports.create'));

    $response->assertSuccessful();
    $response->assertViewIs('additions_report.create');
});

test('store creates an additions report for the authenticated employee', function () {
    Event::fake([AdditionsReportCreatedEvent::class]);
    $user = additionsReportUser(['additions-reports.create']);
    $project = Project::factory()->create();
    $involvedEmployee = Employee::factory()->create();

    $response = $this->actingAs($user)->post(route('additions-reports.store'), [
        'project_id' => $project->id,
        'services_provided_on' => '2026-01-05',
        'hours' => 0.5,
        'weather' => 'sunny',
        'minimum_temperature' => 10,
        'maximum_temperature' => 20,
        'comment' => 'Test comment',
        'involved_ids' => [$involvedEmployee->person_id],
    ]);

    $report = AdditionsReport::sole();

    $response->assertRedirect(route('additions-reports.show', $report));
    expect($report->employee_id)->toBe($user->employee_id);
    expect($report->status)->toBe('new');
    expect($report->involvedEmployees()->count())->toBe(1);
    Event::assertDispatched(AdditionsReportCreatedEvent::class);
});

test('store is forbidden without create permission', function () {
    $user = additionsReportUser();
    $project = Project::factory()->create();
    $involvedEmployee = Employee::factory()->create();

    $response = $this->actingAs($user)->post(route('additions-reports.store'), [
        'project_id' => $project->id,
        'services_provided_on' => '2026-01-05',
        'hours' => 0.5,
        'weather' => 'sunny',
        'minimum_temperature' => 10,
        'maximum_temperature' => 20,
        'comment' => 'Test comment',
        'involved_ids' => [$involvedEmployee->person_id],
    ]);

    $response->assertForbidden();
    expect(AdditionsReport::count())->toBe(0);
});

// show

test('show is allowed for own report with view.own permission', function () {
    $user = additionsReportUser(['additions-reports.view.own']);
    $report = ownAdditionsReport($user);

    $response = $this->actingAs($user)->get(route('additions-reports.show', $report));

    $response->assertSuccessful();
    $response->assertViewIs('additions_report.show');
});

test('show is forbidden for other report without view.other permission', function () {
    $user = additionsReportUser(['additions-reports.view.own']);
    $report = AdditionsReport::factory()->create();

    $response = $this->actingAs($user)->get(route('additions-reports.show', $report));

    $response->assertForbidden();
});

// edit

test('edit is shown for own report with update.own permission', function () {
    $user = additionsReportUser(['additions-reports.update.own']);
    $report = ownAdditionsReport($user);

    $response = $this->actingAs($user)->get(route('additions-reports.edit', $report));

    $response->assertSuccessful();
    $response->assertViewIs('additions_report.edit');
});

// update

test('update persists changes to a non-finished report', function () {
    Event::fake([AdditionsReportUpdatedEvent::class]);
    $user = additionsReportUser(['additions-reports.update.own']);
    $report = ownAdditionsReport($user, ['status' => 'new']);
    $involvedEmployee = Employee::factory()->create();

    $response = $this->actingAs($user)->put(route('additions-reports.update', $report), [
        'project_id' => $report->project_id,
        'services_provided_on' => $report->services_provided_on->format('Y-m-d'),
        'hours' => 0.5,
        'weather' => 'rainy',
        'minimum_temperature' => 5,
        'maximum_temperature' => 15,
        'comment' => 'Updated comment',
        'involved_ids' => [$involvedEmployee->person_id],
    ]);

    $response->assertRedirect(route('additions-reports.show', $report));
    expect($report->fresh()->comment)->toBe('Updated comment');
    expect($report->fresh()->weather)->toBe('rainy');
    Event::assertDispatched(AdditionsReportUpdatedEvent::class);
});

test('update is forbidden on a finished report', function () {
    $user = additionsReportUser(['additions-reports.update.own']);
    $report = ownAdditionsReport($user, ['status' => 'finished']);
    $involvedEmployee = Employee::factory()->create();

    $response = $this->actingAs($user)->put(route('additions-reports.update', $report), [
        'project_id' => $report->project_id,
        'services_provided_on' => $report->services_provided_on->format('Y-m-d'),
        'hours' => 0.5,
        'weather' => 'rainy',
        'minimum_temperature' => 5,
        'maximum_temperature' => 15,
        'comment' => 'Updated comment',
        'involved_ids' => [$involvedEmployee->person_id],
    ]);

    $response->assertForbidden();
});

test('updating a signed report reverts its status to new', function () {
    $user = additionsReportUser(['additions-reports.update.own']);
    $report = ownAdditionsReport($user, ['status' => 'signed']);
    $involvedEmployee = Employee::factory()->create();

    $this->actingAs($user)->put(route('additions-reports.update', $report), [
        'project_id' => $report->project_id,
        'services_provided_on' => $report->services_provided_on->format('Y-m-d'),
        'hours' => 0.5,
        'weather' => 'rainy',
        'minimum_temperature' => 5,
        'maximum_temperature' => 15,
        'comment' => 'Updated comment',
        'involved_ids' => [$involvedEmployee->person_id],
    ]);

    expect($report->fresh()->status)->toBe('new');
});

// destroy

test('destroy removes a non-finished own report', function () {
    $user = additionsReportUser(['additions-reports.delete.own']);
    $report = ownAdditionsReport($user, ['status' => 'new']);

    $response = $this->actingAs($user)->delete(route('additions-reports.destroy', $report));

    $response->assertRedirect(route('additions-reports.index'));
    expect(AdditionsReport::find($report->id))->toBeNull();
});

// sign

test('sign is allowed on a new report and stores a signature', function () {
    Storage::fake('local');
    Event::fake([AdditionsReportSignedEvent::class]);
    $user = additionsReportUser(['additions-reports.get-signature.own']);
    $report = ownAdditionsReport($user, ['status' => 'new']);

    $this->actingAs($user)->post('/additions-reports/'.$report->id.'/sign', [
        'signature' => ADDITIONS_TINY_PNG_BASE64,
    ]);

    expect($report->fresh()->status)->toBe('signed');
    Event::assertDispatched(AdditionsReportSignedEvent::class);
});

test('sign is forbidden on an already signed report', function () {
    $user = additionsReportUser(['additions-reports.get-signature.own']);
    $report = ownAdditionsReport($user, ['status' => 'signed']);

    $response = $this->actingAs($user)->post('/additions-reports/'.$report->id.'/sign', [
        'signature' => ADDITIONS_TINY_PNG_BASE64,
    ]);

    $response->assertForbidden();
});

// finish

test('finish is allowed with approve permission', function () {
    $user = additionsReportUser(['additions-reports.approve']);
    $report = ownAdditionsReport($user, ['status' => 'signed']);

    $response = $this->actingAs($user)->get(route('additions-reports.finish', $report));

    $response->assertRedirect();
    expect($report->fresh()->status)->toBe('finished');
});

test('finish is forbidden without approve permission', function () {
    $user = additionsReportUser(['additions-reports.update.own', 'additions-reports.delete.own']);
    $report = ownAdditionsReport($user, ['status' => 'signed']);

    $response = $this->actingAs($user)->get(route('additions-reports.finish', $report));

    $response->assertForbidden();
    expect($report->fresh()->status)->toBe('signed');
});

// activity log regression

test('finishing a report writes an activity log entry with the new attribute_changes shape', function () {
    $user = additionsReportUser(['additions-reports.approve']);
    $report = ownAdditionsReport($user, ['status' => 'signed']);

    $this->actingAs($user)->get(route('additions-reports.finish', $report));

    $activity = Activity::where('subject_type', AdditionsReport::class)
        ->where('subject_id', $report->id)
        ->latest('id')
        ->first();

    expect($activity)->not->toBeNull();
    expect($activity->attribute_changes['attributes']['status'] ?? null)->toBe('finished');
    expect($activity->attribute_changes['old']['status'] ?? null)->toBe('signed');
});

// email

test('email sends the additions report mail', function () {
    Mail::fake();
    $user = additionsReportUser(['additions-reports.email.own']);
    $report = ownAdditionsReport($user);

    $this->actingAs($user)->post(route('additions-reports.email', $report), [
        'email_to' => [['email' => 'customer@example.com']],
    ]);

    Mail::assertQueued(AdditionsReportMail::class);
});

// email signature request

test('emailSignatureRequest is allowed on a new report and sends the mail', function () {
    Mail::fake();
    $user = additionsReportUser(['additions-reports.send-signature-request.own']);
    $report = ownAdditionsReport($user, ['status' => 'new']);

    $this->actingAs($user)->post('/additions-reports/'.$report->id.'/email-signature-request', [
        'email' => 'customer@example.com',
    ]);

    Mail::assertQueued(AdditionsReportSignatureRequestMail::class);
    expect($report->fresh()->signatureRequest)->not->toBeNull();
});

// email download request

test('emailDownloadRequest is allowed on a signed report and sends the mail', function () {
    Mail::fake();
    $user = additionsReportUser(['additions-reports.send-download-request.own']);
    $report = ownAdditionsReport($user, ['status' => 'signed']);

    $this->actingAs($user)->post('/additions-reports/'.$report->id.'/email-download-request', [
        'email' => 'customer@example.com',
    ]);

    Mail::assertQueued(AdditionsReportDownloadRequestMail::class);
});

// download (real pdflatex)

test('download renders a real pdf for an authorized user', function () {
    $user = additionsReportUser(['additions-reports.createpdf.own']);
    $report = ownAdditionsReport($user);

    $response = $this->actingAs($user)->get(route('additions-reports.download', $report));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'application/pdf');
})->group('pdflatex');

// customer-facing signed routes

test('customer sign form is shown for a valid signature request token', function () {
    $report = ownAdditionsReport(additionsReportUser(), ['status' => 'new']);
    $report->generateSignatureRequest();

    $response = $this->get(route('additions-reports.customer-sign', $report->fresh()->signatureRequest->token));

    $response->assertSuccessful();
    $response->assertViewIs('additions_report.show_customer_signature_request');
});

test('customer sign form warns on an invalid token', function () {
    $response = $this->get(route('additions-reports.customer-sign', 'not-a-real-token'));

    $response->assertSuccessful();
    $response->assertViewHas('additionsReport', null);
    expect(session('warning'))->not->toBeNull();
});

test('customer sign stores a signature and generates a download request', function () {
    Storage::fake('local');
    Event::fake([AdditionsReportSignedEvent::class]);
    $report = ownAdditionsReport(additionsReportUser(), ['status' => 'new']);
    $report->generateSignatureRequest();
    $token = $report->fresh()->signatureRequest->token;

    $response = $this->post(route('additions-reports.customer-sign', $token), [
        'signature' => ADDITIONS_TINY_PNG_BASE64,
    ]);

    $response->assertSuccessful();
    expect($report->fresh()->status)->toBe('signed');
    expect($report->fresh()->downloadRequest)->not->toBeNull();
    Event::assertDispatched(AdditionsReportSignedEvent::class);
});

test('customer download deletes the download request and streams a real pdf', function () {
    $report = ownAdditionsReport(additionsReportUser(), ['status' => 'signed']);
    $report->generateDownloadRequest();
    $token = $report->fresh()->downloadRequest->token;

    $response = $this->get(route('additions-reports.customer-download', $token));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'application/pdf');
    expect($report->fresh()->downloadRequest)->toBeNull();
})->group('pdflatex');

test('customer download warns on an invalid token instead of erroring', function () {
    $response = $this->get(route('additions-reports.customer-download', 'not-a-real-token'));

    $response->assertSuccessful();
    $response->assertViewIs('additions_report.download_invalid');
    expect(session('warning'))->not->toBeNull();
});

test('customer email download request queues the mail for a valid token', function () {
    Mail::fake();
    $report = ownAdditionsReport(additionsReportUser(), ['status' => 'signed']);
    $report->generateDownloadRequest();
    $token = $report->fresh()->downloadRequest->token;

    $response = $this->post(route('additions-reports.customer-email-download-request', $token), [
        'email' => 'customer@example.com',
    ]);

    $response->assertSuccessful();
    Mail::assertQueued(AdditionsReportDownloadRequestMail::class);
});

test('customer email download request warns on an invalid token instead of erroring', function () {
    Mail::fake();

    $response = $this->post(route('additions-reports.customer-email-download-request', 'not-a-real-token'), [
        'email' => 'customer@example.com',
    ]);

    $response->assertSuccessful();
    $response->assertViewIs('additions_report.download_invalid');
    expect(session('warning'))->not->toBeNull();
    Mail::assertNothingQueued();
});
