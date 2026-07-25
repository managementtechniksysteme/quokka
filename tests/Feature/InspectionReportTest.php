<?php

namespace Tests\Feature;

use App\Events\InspectionReportCreatedEvent;
use App\Events\InspectionReportSignedEvent;
use App\Events\InspectionReportUpdatedEvent;
use App\Mail\InspectionReportDownloadRequestMail;
use App\Mail\InspectionReportMail;
use App\Mail\InspectionReportSignatureRequestMail;
use App\Models\InspectionReport;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

const INSPECTION_TINY_PNG_BASE64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';

function inspectionReportUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

function ownInspectionReport(User $user, array $attributes = []): InspectionReport
{
    return InspectionReport::factory()->create(array_merge(['employee_id' => $user->employee_id], $attributes));
}

function inspectionReportPayload(array $overrides = []): array
{
    return array_merge([
        'inspected_on' => '2026-01-05',
        'weather' => 'sunny',
        'equipment_type' => 'UVC',
        'equipment_identifier' => 'EQ-0001',
        'uvc_lamp_type' => 'Standard',
        'uvc_lamp_quantity' => 1,
        'uvc_lamp_operating_hours' => 100,
        'uvc_lamp_impulses' => 10,
        'uvc_lamp_uv_intensity_arrival' => 50,
        'uvc_lamp_uv_intensity_departure' => 45,
        'uvc_lamp_values_unit' => 'percent',
        'uvc_sensor_type' => 'Standard',
        'uvc_sensor_identifier' => 'SN-0001',
        'uvc_sensor_pre_alarm' => 30,
        'uvc_sensor_cut_off_point' => 20,
        'uvc_sensor_values_unit' => 'percent',
        'water_flow_rate' => 10,
        'water_minimum_uv_transmission' => 80,
        'water_measured_uv_transmission' => 90,
        'comment' => 'Test comment',
    ], $overrides);
}

// index

test('index is shown for a user with view permission', function () {
    $user = inspectionReportUser(['inspection-reports.view.own']);

    $response = $this->actingAs($user)->get(route('inspection-reports.index'));

    $response->assertSuccessful();
    $response->assertViewIs('inspection_report.index');
});

test('index is forbidden without view permission', function () {
    $user = inspectionReportUser();

    $response = $this->actingAs($user)->get(route('inspection-reports.index'));

    $response->assertForbidden();
});

// create / store

test('create form is shown for a user with create permission', function () {
    $user = inspectionReportUser(['inspection-reports.create']);

    $response = $this->actingAs($user)->get(route('inspection-reports.create'));

    $response->assertSuccessful();
    $response->assertViewIs('inspection_report.create');
});

test('store creates an inspection report for the authenticated employee', function () {
    Event::fake([InspectionReportCreatedEvent::class]);
    $user = inspectionReportUser(['inspection-reports.create']);
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->post(route('inspection-reports.store'), inspectionReportPayload([
        'project_id' => $project->id,
    ]));

    $report = InspectionReport::sole();

    $response->assertRedirect(route('inspection-reports.show', $report));
    expect($report->employee_id)->toBe($user->employee_id);
    expect($report->status)->toBe('new');
    Event::assertDispatched(InspectionReportCreatedEvent::class);
});

test('store is forbidden without create permission', function () {
    $user = inspectionReportUser();
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->post(route('inspection-reports.store'), inspectionReportPayload([
        'project_id' => $project->id,
    ]));

    $response->assertForbidden();
    expect(InspectionReport::count())->toBe(0);
});

// show

test('show is allowed for own report with view.own permission', function () {
    $user = inspectionReportUser(['inspection-reports.view.own']);
    $report = ownInspectionReport($user);

    $response = $this->actingAs($user)->get(route('inspection-reports.show', $report));

    $response->assertSuccessful();
    $response->assertViewIs('inspection_report.show');
});

test('show is forbidden for other report without view.other permission', function () {
    $user = inspectionReportUser(['inspection-reports.view.own']);
    $report = InspectionReport::factory()->create();

    $response = $this->actingAs($user)->get(route('inspection-reports.show', $report));

    $response->assertForbidden();
});

// edit

test('edit is shown for own report with update.own permission', function () {
    $user = inspectionReportUser(['inspection-reports.update.own']);
    $report = ownInspectionReport($user);

    $response = $this->actingAs($user)->get(route('inspection-reports.edit', $report));

    $response->assertSuccessful();
    $response->assertViewIs('inspection_report.edit');
});

// update

test('update persists changes to a non-finished report', function () {
    Event::fake([InspectionReportUpdatedEvent::class]);
    $user = inspectionReportUser(['inspection-reports.update.own']);
    $report = ownInspectionReport($user, ['status' => 'new']);

    $response = $this->actingAs($user)->put(route('inspection-reports.update', $report), inspectionReportPayload([
        'project_id' => $report->project_id,
        'inspected_on' => $report->inspected_on->format('Y-m-d'),
        'equipment_identifier' => $report->equipment_identifier,
        'comment' => 'Updated comment',
    ]));

    $response->assertRedirect(route('inspection-reports.show', $report));
    expect($report->fresh()->comment)->toBe('Updated comment');
    Event::assertDispatched(InspectionReportUpdatedEvent::class);
});

test('update is forbidden on a finished report', function () {
    $user = inspectionReportUser(['inspection-reports.update.own']);
    $report = ownInspectionReport($user, ['status' => 'finished']);

    $response = $this->actingAs($user)->put(route('inspection-reports.update', $report), inspectionReportPayload([
        'project_id' => $report->project_id,
        'inspected_on' => $report->inspected_on->format('Y-m-d'),
        'equipment_identifier' => $report->equipment_identifier,
        'comment' => 'Updated comment',
    ]));

    $response->assertForbidden();
});

test('updating a signed report reverts its status to new', function () {
    $user = inspectionReportUser(['inspection-reports.update.own']);
    $report = ownInspectionReport($user, ['status' => 'signed']);

    $this->actingAs($user)->put(route('inspection-reports.update', $report), inspectionReportPayload([
        'project_id' => $report->project_id,
        'inspected_on' => $report->inspected_on->format('Y-m-d'),
        'equipment_identifier' => $report->equipment_identifier,
        'comment' => 'Updated comment',
    ]));

    expect($report->fresh()->status)->toBe('new');
});

// destroy

test('destroy removes a non-finished own report', function () {
    $user = inspectionReportUser(['inspection-reports.delete.own']);
    $report = ownInspectionReport($user, ['status' => 'new']);

    $response = $this->actingAs($user)->delete(route('inspection-reports.destroy', $report));

    $response->assertRedirect(route('inspection-reports.index'));
    expect(InspectionReport::find($report->id))->toBeNull();
});

// sign

test('sign is allowed on a new report and stores a signature', function () {
    Storage::fake('local');
    Event::fake([InspectionReportSignedEvent::class]);
    $user = inspectionReportUser(['inspection-reports.get-signature.own']);
    $report = ownInspectionReport($user, ['status' => 'new']);

    $this->actingAs($user)->post('/inspection-reports/'.$report->id.'/sign', [
        'signature' => INSPECTION_TINY_PNG_BASE64,
    ]);

    expect($report->fresh()->status)->toBe('signed');
    Event::assertDispatched(InspectionReportSignedEvent::class);
});

test('sign is forbidden on an already signed report', function () {
    $user = inspectionReportUser(['inspection-reports.get-signature.own']);
    $report = ownInspectionReport($user, ['status' => 'signed']);

    $response = $this->actingAs($user)->post('/inspection-reports/'.$report->id.'/sign', [
        'signature' => INSPECTION_TINY_PNG_BASE64,
    ]);

    $response->assertForbidden();
});

// finish

test('finish is allowed with approve permission', function () {
    $user = inspectionReportUser(['inspection-reports.approve']);
    $report = ownInspectionReport($user, ['status' => 'signed']);

    $response = $this->actingAs($user)->get(route('inspection-reports.finish', $report));

    $response->assertRedirect();
    expect($report->fresh()->status)->toBe('finished');
});

test('finish is forbidden without approve permission', function () {
    $user = inspectionReportUser(['inspection-reports.update.own', 'inspection-reports.delete.own']);
    $report = ownInspectionReport($user, ['status' => 'signed']);

    $response = $this->actingAs($user)->get(route('inspection-reports.finish', $report));

    $response->assertForbidden();
    expect($report->fresh()->status)->toBe('signed');
});

// activity log regression

test('finishing a report writes an activity log entry with the new attribute_changes shape', function () {
    $user = inspectionReportUser(['inspection-reports.approve']);
    $report = ownInspectionReport($user, ['status' => 'signed']);

    $this->actingAs($user)->get(route('inspection-reports.finish', $report));

    $activity = Activity::where('subject_type', InspectionReport::class)
        ->where('subject_id', $report->id)
        ->latest('id')
        ->first();

    expect($activity)->not->toBeNull();
    expect($activity->attribute_changes['attributes']['status'] ?? null)->toBe('finished');
    expect($activity->attribute_changes['old']['status'] ?? null)->toBe('signed');
});

// email

test('email sends the inspection report mail', function () {
    Mail::fake();
    $user = inspectionReportUser(['inspection-reports.email.own']);
    $report = ownInspectionReport($user);

    $this->actingAs($user)->post(route('inspection-reports.email', $report), [
        'email_to' => [['email' => 'customer@example.com']],
    ]);

    Mail::assertQueued(InspectionReportMail::class);
});

// email signature request

test('emailSignatureRequest is allowed on a new report and sends the mail', function () {
    Mail::fake();
    $user = inspectionReportUser(['inspection-reports.send-signature-request.own']);
    $report = ownInspectionReport($user, ['status' => 'new']);

    $this->actingAs($user)->post('/inspection-reports/'.$report->id.'/email-signature-request', [
        'email' => 'customer@example.com',
    ]);

    Mail::assertQueued(InspectionReportSignatureRequestMail::class);
    expect($report->fresh()->signatureRequest)->not->toBeNull();
});

// email download request

test('emailDownloadRequest is allowed on a signed report and sends the mail', function () {
    Mail::fake();
    $user = inspectionReportUser(['inspection-reports.send-download-request.own']);
    $report = ownInspectionReport($user, ['status' => 'signed']);

    $this->actingAs($user)->post('/inspection-reports/'.$report->id.'/email-download-request', [
        'email' => 'customer@example.com',
    ]);

    Mail::assertQueued(InspectionReportDownloadRequestMail::class);
});

// download (real pdflatex)

test('download renders a real pdf for an authorized user', function () {
    $user = inspectionReportUser(['inspection-reports.createpdf.own']);
    $report = ownInspectionReport($user);

    $response = $this->actingAs($user)->get(route('inspection-reports.download', $report));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'application/pdf');
})->group('pdflatex');

// customer-facing signed routes

test('customer sign form is shown for a valid signature request token', function () {
    $report = ownInspectionReport(inspectionReportUser(), ['status' => 'new']);
    $report->generateSignatureRequest();

    $response = $this->get(route('inspection-reports.customer-sign', $report->fresh()->signatureRequest->token));

    $response->assertSuccessful();
    $response->assertViewIs('inspection_report.show_customer_signature_request');
});

test('customer sign form warns on an invalid token', function () {
    $response = $this->get(route('inspection-reports.customer-sign', 'not-a-real-token'));

    $response->assertSuccessful();
    $response->assertViewHas('inspectionReport', null);
    expect(session('warning'))->not->toBeNull();
});

test('customer sign stores a signature and generates a download request', function () {
    Storage::fake('local');
    Event::fake([InspectionReportSignedEvent::class]);
    $report = ownInspectionReport(inspectionReportUser(), ['status' => 'new']);
    $report->generateSignatureRequest();
    $token = $report->fresh()->signatureRequest->token;

    $response = $this->post(route('inspection-reports.customer-sign', $token), [
        'signature' => INSPECTION_TINY_PNG_BASE64,
    ]);

    $response->assertSuccessful();
    expect($report->fresh()->status)->toBe('signed');
    expect($report->fresh()->downloadRequest)->not->toBeNull();
    Event::assertDispatched(InspectionReportSignedEvent::class);
});

test('customer download deletes the download request and streams a real pdf', function () {
    $report = ownInspectionReport(inspectionReportUser(), ['status' => 'signed']);
    $report->generateDownloadRequest();
    $token = $report->fresh()->downloadRequest->token;

    $response = $this->get(route('inspection-reports.customer-download', $token));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'application/pdf');
    expect($report->fresh()->downloadRequest)->toBeNull();
})->group('pdflatex');

test('customer download warns on an invalid token instead of erroring', function () {
    $response = $this->get(route('inspection-reports.customer-download', 'not-a-real-token'));

    $response->assertSuccessful();
    $response->assertViewIs('inspection_report.download_invalid');
    expect(session('warning'))->not->toBeNull();
});

test('customer email download request queues the mail for a valid token', function () {
    Mail::fake();
    $report = ownInspectionReport(inspectionReportUser(), ['status' => 'signed']);
    $report->generateDownloadRequest();
    $token = $report->fresh()->downloadRequest->token;

    $response = $this->post(route('inspection-reports.customer-email-download-request', $token), [
        'email' => 'customer@example.com',
    ]);

    $response->assertSuccessful();
    Mail::assertQueued(InspectionReportDownloadRequestMail::class);
});

test('customer email download request warns on an invalid token instead of erroring', function () {
    Mail::fake();

    $response = $this->post(route('inspection-reports.customer-email-download-request', 'not-a-real-token'), [
        'email' => 'customer@example.com',
    ]);

    $response->assertSuccessful();
    $response->assertViewIs('inspection_report.download_invalid');
    expect(session('warning'))->not->toBeNull();
    Mail::assertNothingQueued();
});
