<?php

namespace Tests\Feature;

use App\Events\FlowMeterInspectionReportCreatedEvent;
use App\Events\FlowMeterInspectionReportSignedEvent;
use App\Events\FlowMeterInspectionReportUpdatedEvent;
use App\Mail\FlowMeterInspectionReportDownloadRequestMail;
use App\Mail\FlowMeterInspectionReportMail;
use App\Mail\FlowMeterInspectionReportSignatureRequestMail;
use App\Models\FlowMeterInspectionReport;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

const FLOWMETER_TINY_PNG_BASE64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';

function flowMeterInspectionReportUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

function ownFlowMeterInspectionReport(User $user, array $attributes = []): FlowMeterInspectionReport
{
    return FlowMeterInspectionReport::factory()->create(array_merge(['employee_id' => $user->employee_id], $attributes));
}

// Minimal valid HTTP payload matching FlowMeterInspectionReportStoreRequest's 89 required
// fields, avoiding the conditional cascades (chooses the "volumetric" comparison process,
// a non-"interface" transformer level unit, and omits every optional field whose presence
// would trigger an extra required field) so the payload stays as small as possible.
function flowMeterInspectionReportPayload(array $overrides = []): array
{
    return array_merge([
        'inspected_on' => '2026-01-05',
        'weather' => 'sunny',
        'temperature' => 15,
        'equipment_identifier' => 'EQ-0001',
        'measuring_point' => 'MP-1',
        'installation_point' => 'IP-1',
        'medium' => 'Abwasser',
        'responsible_person' => 'Jane Doe',
        'responsible_person_instructed_on' => '2026-01-01',
        'instructor' => 'John Doe',
        'profile_outer_diameter' => 200,
        'profile_wall_thickness' => 10,
        'profile_material' => 'PVC',
        'without_cross_section_reduction' => true,
        'fully_filled' => true,
        'speed_measurement_type' => 'radar',
        'documentation_existent' => true,
        'inspection_book_existent' => true,
        'inspection_requirements_existent' => true,
        'documentation_current' => true,
        'q_min' => 1,
        'q_max' => 100,
        'flow_range_type' => 'guess',
        'flow_rate_meter' => 'Meter A',
        'flow_rate_meter_make' => 'Make A',
        'flow_rate_meter_type' => 'Type A',
        'flow_rate_meter_identifier' => 'ID-A',
        'measurement_transformer_point' => 'local',
        'measurement_transformer_make' => 'Make B',
        'measurement_transformer_type' => 'Type B',
        'measurement_transformer_identifier' => 'ID-B',
        'measurement_transformer_level_unit' => 'mA',
        'measurement_transformer_minimum_level' => 4,
        'measurement_transformer_maximum_level' => 20,
        'measurement_transformer_range_100_percent' => 100,
        'measurement_transformer_impulses' => 10,
        'measurement_transformer_data_logging' => 'yes',
        'headwater_pipe_diameter' => 150,
        'headwater_calming_section' => '5m',
        'headwater_calming_section_assessment' => 'ok',
        'measurement_section_installation_according_to_manufacturer' => true,
        'measurement_section_pipe_diameter' => 150,
        'tailwater_pipe_diameter' => 150,
        'tailwater_pipe_fully_filled' => true,
        'tailwater_runout_section_assessment' => 'ok',
        'tailwater_measurement_pipe_can_run_dry' => false,
        'tailwater_flow_conditions_influenced' => false,
        'comparison_measurements_process' => 'volumetric',
        'comparison_measurement_volumetric_basin' => 'Basin A',
        'comparison_measurement_volumetric_basin_cross_section_area' => 5,
        'comparison_measurement_volumetric_height_measurement_equipment' => 'Gauge A',
        'measurements' => [
            100 => [
                'started_at' => '2026-01-05T08:00',
                'ended_at' => '2026-01-05T10:00',
                'measurement_transformer_reading_sum' => 10,
                'comparison_measurement_sum' => 10,
                'measurement_difference' => 0,
            ],
        ],
        'measurement_difference_up_to_30_q_max' => 1,
        'measurement_difference_above_30_q_max' => 1,
        'reading_difference_up_to_30_q_max' => 1,
        'reading_difference_above_30_q_max' => 1,
        'equipment_in_tolerance_range' => true,
        'comment' => 'Test comment',
    ], $overrides);
}

// index

test('index is shown for a user with view permission', function () {
    $user = flowMeterInspectionReportUser(['flow-meter-inspection-reports.view.own']);

    $response = $this->actingAs($user)->get(route('flow-meter-inspection-reports.index'));

    $response->assertSuccessful();
    $response->assertViewIs('flow_meter_inspection_report.index');
});

test('index is forbidden without view permission', function () {
    $user = flowMeterInspectionReportUser();

    $response = $this->actingAs($user)->get(route('flow-meter-inspection-reports.index'));

    $response->assertForbidden();
});

// create / store

test('create form is shown for a user with create permission', function () {
    $user = flowMeterInspectionReportUser(['flow-meter-inspection-reports.create']);

    $response = $this->actingAs($user)->get(route('flow-meter-inspection-reports.create'));

    $response->assertSuccessful();
    $response->assertViewIs('flow_meter_inspection_report.create');
});

test('store creates a flow meter inspection report for the authenticated employee', function () {
    Event::fake([FlowMeterInspectionReportCreatedEvent::class]);
    $user = flowMeterInspectionReportUser(['flow-meter-inspection-reports.create']);
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->post(route('flow-meter-inspection-reports.store'), flowMeterInspectionReportPayload([
        'project_id' => $project->id,
    ]));

    $report = FlowMeterInspectionReport::sole();

    $response->assertRedirect(route('flow-meter-inspection-reports.show', $report));
    expect($report->employee_id)->toBe($user->employee_id);
    expect($report->status)->toBe('new');
    expect($report->measurements()->where('q_percent', 100)->exists())->toBeTrue();
    Event::assertDispatched(FlowMeterInspectionReportCreatedEvent::class);
});

test('store is forbidden without create permission', function () {
    $user = flowMeterInspectionReportUser();
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->post(route('flow-meter-inspection-reports.store'), flowMeterInspectionReportPayload([
        'project_id' => $project->id,
    ]));

    $response->assertForbidden();
    expect(FlowMeterInspectionReport::count())->toBe(0);
});

// show

test('show is allowed for own report with view.own permission', function () {
    $user = flowMeterInspectionReportUser(['flow-meter-inspection-reports.view.own']);
    $report = ownFlowMeterInspectionReport($user);

    $response = $this->actingAs($user)->get(route('flow-meter-inspection-reports.show', $report));

    $response->assertSuccessful();
    $response->assertViewIs('flow_meter_inspection_report.show');
});

test('show is forbidden for other report without view.other permission', function () {
    $user = flowMeterInspectionReportUser(['flow-meter-inspection-reports.view.own']);
    $report = FlowMeterInspectionReport::factory()->create();

    $response = $this->actingAs($user)->get(route('flow-meter-inspection-reports.show', $report));

    $response->assertForbidden();
});

// edit

test('edit is shown for own report with update.own permission', function () {
    $user = flowMeterInspectionReportUser(['flow-meter-inspection-reports.update.own']);
    $report = ownFlowMeterInspectionReport($user);

    $response = $this->actingAs($user)->get(route('flow-meter-inspection-reports.edit', $report));

    $response->assertSuccessful();
    $response->assertViewIs('flow_meter_inspection_report.edit');
});

// update

test('update persists changes to a non-finished report', function () {
    Event::fake([FlowMeterInspectionReportUpdatedEvent::class]);
    $user = flowMeterInspectionReportUser(['flow-meter-inspection-reports.update.own']);
    $report = ownFlowMeterInspectionReport($user, ['status' => 'new']);

    $response = $this->actingAs($user)->put(route('flow-meter-inspection-reports.update', $report), flowMeterInspectionReportPayload([
        'project_id' => $report->project_id,
        'inspected_on' => $report->inspected_on->format('Y-m-d'),
        'equipment_identifier' => $report->equipment_identifier,
        'comment' => 'Updated comment',
    ]));

    $response->assertRedirect(route('flow-meter-inspection-reports.show', $report));
    expect($report->fresh()->comment)->toBe('Updated comment');
    Event::assertDispatched(FlowMeterInspectionReportUpdatedEvent::class);
});

test('update is forbidden on a finished report', function () {
    $user = flowMeterInspectionReportUser(['flow-meter-inspection-reports.update.own']);
    $report = ownFlowMeterInspectionReport($user, ['status' => 'finished']);

    $response = $this->actingAs($user)->put(route('flow-meter-inspection-reports.update', $report), flowMeterInspectionReportPayload([
        'project_id' => $report->project_id,
        'inspected_on' => $report->inspected_on->format('Y-m-d'),
        'equipment_identifier' => $report->equipment_identifier,
        'comment' => 'Updated comment',
    ]));

    $response->assertForbidden();
});

test('updating a signed report reverts its status to new', function () {
    $user = flowMeterInspectionReportUser(['flow-meter-inspection-reports.update.own']);
    $report = ownFlowMeterInspectionReport($user, ['status' => 'signed']);

    $this->actingAs($user)->put(route('flow-meter-inspection-reports.update', $report), flowMeterInspectionReportPayload([
        'project_id' => $report->project_id,
        'inspected_on' => $report->inspected_on->format('Y-m-d'),
        'equipment_identifier' => $report->equipment_identifier,
        'comment' => 'Updated comment',
    ]));

    expect($report->fresh()->status)->toBe('new');
});

// destroy

test('destroy removes a non-finished own report', function () {
    $user = flowMeterInspectionReportUser(['flow-meter-inspection-reports.delete.own']);
    $report = ownFlowMeterInspectionReport($user, ['status' => 'new']);

    $response = $this->actingAs($user)->delete(route('flow-meter-inspection-reports.destroy', $report));

    $response->assertRedirect(route('flow-meter-inspection-reports.index'));
    expect(FlowMeterInspectionReport::find($report->id))->toBeNull();
});

// sign

test('sign is allowed on a new report and stores a signature', function () {
    Storage::fake('local');
    Event::fake([FlowMeterInspectionReportSignedEvent::class]);
    $user = flowMeterInspectionReportUser(['flow-meter-inspection-reports.get-signature.own']);
    $report = ownFlowMeterInspectionReport($user, ['status' => 'new']);

    $this->actingAs($user)->post('/flow-meter-inspection-reports/'.$report->id.'/sign', [
        'signature' => FLOWMETER_TINY_PNG_BASE64,
    ]);

    expect($report->fresh()->status)->toBe('signed');
    Event::assertDispatched(FlowMeterInspectionReportSignedEvent::class);
});

test('sign is forbidden on an already signed report', function () {
    $user = flowMeterInspectionReportUser(['flow-meter-inspection-reports.get-signature.own']);
    $report = ownFlowMeterInspectionReport($user, ['status' => 'signed']);

    $response = $this->actingAs($user)->post('/flow-meter-inspection-reports/'.$report->id.'/sign', [
        'signature' => FLOWMETER_TINY_PNG_BASE64,
    ]);

    $response->assertForbidden();
});

// finish

test('finish is allowed with approve permission', function () {
    $user = flowMeterInspectionReportUser(['flow-meter-inspection-reports.approve']);
    $report = ownFlowMeterInspectionReport($user, ['status' => 'signed']);

    $response = $this->actingAs($user)->get(route('flow-meter-inspection-reports.finish', $report));

    $response->assertRedirect();
    expect($report->fresh()->status)->toBe('finished');
});

test('finish is forbidden without approve permission', function () {
    $user = flowMeterInspectionReportUser(['flow-meter-inspection-reports.update.own', 'flow-meter-inspection-reports.delete.own']);
    $report = ownFlowMeterInspectionReport($user, ['status' => 'signed']);

    $response = $this->actingAs($user)->get(route('flow-meter-inspection-reports.finish', $report));

    $response->assertForbidden();
    expect($report->fresh()->status)->toBe('signed');
});

// activity log regression

test('finishing a report writes an activity log entry with the new attribute_changes shape', function () {
    $user = flowMeterInspectionReportUser(['flow-meter-inspection-reports.approve']);
    $report = ownFlowMeterInspectionReport($user, ['status' => 'signed']);

    $this->actingAs($user)->get(route('flow-meter-inspection-reports.finish', $report));

    $activity = Activity::where('subject_type', FlowMeterInspectionReport::class)
        ->where('subject_id', $report->id)
        ->latest('id')
        ->first();

    expect($activity)->not->toBeNull();
    expect($activity->attribute_changes['attributes']['status'] ?? null)->toBe('finished');
    expect($activity->attribute_changes['old']['status'] ?? null)->toBe('signed');
});

// email

test('email sends the flow meter inspection report mail', function () {
    Mail::fake();
    $user = flowMeterInspectionReportUser(['flow-meter-inspection-reports.email.own']);
    $report = ownFlowMeterInspectionReport($user);

    $this->actingAs($user)->post(route('flow-meter-inspection-reports.email', $report), [
        'email_to' => [['email' => 'customer@example.com']],
    ]);

    Mail::assertQueued(FlowMeterInspectionReportMail::class);
});

// email signature request

test('emailSignatureRequest is allowed on a new report and sends the mail', function () {
    Mail::fake();
    $user = flowMeterInspectionReportUser(['flow-meter-inspection-reports.send-signature-request.own']);
    $report = ownFlowMeterInspectionReport($user, ['status' => 'new']);

    $this->actingAs($user)->post('/flow-meter-inspection-reports/'.$report->id.'/email-signature-request', [
        'email' => 'customer@example.com',
    ]);

    Mail::assertQueued(FlowMeterInspectionReportSignatureRequestMail::class);
    expect($report->fresh()->signatureRequest)->not->toBeNull();
});

// email download request

test('emailDownloadRequest is allowed on a signed report and sends the mail', function () {
    Mail::fake();
    $user = flowMeterInspectionReportUser(['flow-meter-inspection-reports.send-download-request.own']);
    $report = ownFlowMeterInspectionReport($user, ['status' => 'signed']);

    $this->actingAs($user)->post('/flow-meter-inspection-reports/'.$report->id.'/email-download-request', [
        'email' => 'customer@example.com',
    ]);

    Mail::assertQueued(FlowMeterInspectionReportDownloadRequestMail::class);
});

// download (real pdflatex)

test('download renders a real pdf for an authorized user', function () {
    $user = flowMeterInspectionReportUser(['flow-meter-inspection-reports.createpdf.own']);
    $report = ownFlowMeterInspectionReport($user);

    $response = $this->actingAs($user)->get(route('flow-meter-inspection-reports.download', $report));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'application/pdf');
})->group('pdflatex');

// customer-facing signed routes

test('customer sign form is shown for a valid signature request token', function () {
    $report = ownFlowMeterInspectionReport(flowMeterInspectionReportUser(), ['status' => 'new']);
    $report->generateSignatureRequest();

    $response = $this->get(route('flow-meter-inspection-reports.customer-sign', $report->fresh()->signatureRequest->token));

    $response->assertSuccessful();
    $response->assertViewIs('flow_meter_inspection_report.show_customer_signature_request');
});

test('customer sign form warns on an invalid token', function () {
    $response = $this->get(route('flow-meter-inspection-reports.customer-sign', 'not-a-real-token'));

    $response->assertSuccessful();
    $response->assertViewHas('flowMeterInspectionReport', null);
    expect(session('warning'))->not->toBeNull();
});

test('customer sign stores a signature and generates a download request', function () {
    Storage::fake('local');
    Event::fake([FlowMeterInspectionReportSignedEvent::class]);
    $report = ownFlowMeterInspectionReport(flowMeterInspectionReportUser(), ['status' => 'new']);
    $report->generateSignatureRequest();
    $token = $report->fresh()->signatureRequest->token;

    $response = $this->post(route('flow-meter-inspection-reports.customer-sign', $token), [
        'signature' => FLOWMETER_TINY_PNG_BASE64,
    ]);

    $response->assertSuccessful();
    expect($report->fresh()->status)->toBe('signed');
    expect($report->fresh()->downloadRequest)->not->toBeNull();
    Event::assertDispatched(FlowMeterInspectionReportSignedEvent::class);
});

test('customer download deletes the download request and streams a real pdf', function () {
    $report = ownFlowMeterInspectionReport(flowMeterInspectionReportUser(), ['status' => 'signed']);
    $report->generateDownloadRequest();
    $token = $report->fresh()->downloadRequest->token;

    $response = $this->get(route('flow-meter-inspection-reports.customer-download', $token));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'application/pdf');
    expect($report->fresh()->downloadRequest)->toBeNull();
})->group('pdflatex');

test('customer download warns on an invalid token instead of erroring', function () {
    $response = $this->get(route('flow-meter-inspection-reports.customer-download', 'not-a-real-token'));

    $response->assertSuccessful();
    $response->assertViewIs('flow_meter_inspection_report.download_invalid');
    expect(session('warning'))->not->toBeNull();
});

test('customer email download request queues the mail for a valid token', function () {
    Mail::fake();
    $report = ownFlowMeterInspectionReport(flowMeterInspectionReportUser(), ['status' => 'signed']);
    $report->generateDownloadRequest();
    $token = $report->fresh()->downloadRequest->token;

    $response = $this->post(route('flow-meter-inspection-reports.customer-email-download-request', $token), [
        'email' => 'customer@example.com',
    ]);

    $response->assertSuccessful();
    Mail::assertQueued(FlowMeterInspectionReportDownloadRequestMail::class);
});

test('customer email download request warns on an invalid token instead of erroring', function () {
    Mail::fake();

    $response = $this->post(route('flow-meter-inspection-reports.customer-email-download-request', 'not-a-real-token'), [
        'email' => 'customer@example.com',
    ]);

    $response->assertSuccessful();
    $response->assertViewIs('flow_meter_inspection_report.download_invalid');
    expect(session('warning'))->not->toBeNull();
    Mail::assertNothingQueued();
});
