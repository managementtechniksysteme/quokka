<?php

namespace Tests\Feature;

use App\Events\DeliveryNoteSignedEvent;
use App\Mail\DeliveryNoteDownloadRequestMail;
use App\Mail\DeliveryNoteMail;
use App\Mail\DeliveryNoteSignatureRequestMail;
use App\Models\DeliveryNote;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;
use ZsgsDesign\PDFConverter\Latex;
use ZsgsDesign\PDFConverter\RawTex;

const DELIVERY_NOTE_TINY_PNG_BASE64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';

// downloadPDF() embeds the uploaded document into the signed output via \includepdf, which
// needs a genuinely valid PDF (not just arbitrary bytes) - generate one for real via the
// same pdflatex install the app uses, rather than hand-rolling PDF byte offsets.
function realPdfUploadedFile(): UploadedFile
{
    $path = sys_get_temp_dir().'/'.Str::random(10).'.pdf';

    (new Latex())
        ->binPath('/usr/bin/pdflatex')
        ->view(new RawTex('\documentclass{article}\begin{document}Test\end{document}'))
        ->savePdf($path);

    return new UploadedFile($path, 'document.pdf', 'application/pdf', null, true);
}

function deliveryNoteUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

function ownDeliveryNote(User $user, array $attributes = []): DeliveryNote
{
    return DeliveryNote::factory()->create(array_merge(['employee_id' => $user->employee_id], $attributes));
}

test('index is shown for a user with view permission', function () {
    $user = deliveryNoteUser(['delivery-notes.view']);

    $response = $this->actingAs($user)->get(route('delivery-notes.index'));

    $response->assertSuccessful();
    $response->assertViewIs('delivery_note.index');
});

test('index is forbidden without view permission', function () {
    $user = deliveryNoteUser();

    $response = $this->actingAs($user)->get(route('delivery-notes.index'));

    $response->assertForbidden();
});

// create / store

test('create form is shown for a user with create permission', function () {
    $user = deliveryNoteUser(['delivery-notes.create']);

    $response = $this->actingAs($user)->get(route('delivery-notes.create'));

    $response->assertSuccessful();
    $response->assertViewIs('delivery_note.create');
});

test('store creates a delivery note for the authenticated employee', function () {
    Storage::fake('local');
    $user = deliveryNoteUser(['delivery-notes.create']);
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->post(route('delivery-notes.store'), [
        'project_id' => $project->id,
        'written_on' => '2026-01-05',
        'title' => 'Note A',
        'comment' => 'Test comment',
        'document' => UploadedFile::fake()->create('document.pdf', 10, 'application/pdf'),
    ]);

    $note = DeliveryNote::sole();

    $response->assertRedirect(route('delivery-notes.show', $note));
    expect($note->employee_id)->toBe($user->employee_id);
    expect($note->status)->toBe('new');
    expect($note->document())->not->toBeNull();
});

test('store is forbidden without create permission', function () {
    Storage::fake('local');
    $user = deliveryNoteUser();
    $project = Project::factory()->create();

    $response = $this->actingAs($user)->post(route('delivery-notes.store'), [
        'project_id' => $project->id,
        'written_on' => '2026-01-05',
        'title' => 'Note A',
        'document' => UploadedFile::fake()->create('document.pdf', 10, 'application/pdf'),
    ]);

    $response->assertForbidden();
    expect(DeliveryNote::count())->toBe(0);
});

// show

test('show is allowed with view permission', function () {
    $user = deliveryNoteUser(['delivery-notes.view']);
    $note = ownDeliveryNote($user);

    $response = $this->actingAs($user)->get(route('delivery-notes.show', $note));

    $response->assertSuccessful();
    $response->assertViewIs('delivery_note.show');
});

test('show is forbidden without view permission', function () {
    $user = deliveryNoteUser();
    $note = ownDeliveryNote($user);

    $response = $this->actingAs($user)->get(route('delivery-notes.show', $note));

    $response->assertForbidden();
});

// edit

test('edit is shown for a user with update permission', function () {
    $user = deliveryNoteUser(['delivery-notes.update']);
    $note = ownDeliveryNote($user);

    $response = $this->actingAs($user)->get(route('delivery-notes.edit', $note));

    $response->assertSuccessful();
    $response->assertViewIs('delivery_note.edit');
});

// update

test('update persists changes to a non-finished note', function () {
    Storage::fake('local');
    $user = deliveryNoteUser(['delivery-notes.update']);
    $note = ownDeliveryNote($user, ['status' => 'new']);

    $response = $this->actingAs($user)->put(route('delivery-notes.update', $note), [
        'project_id' => $note->project_id,
        'written_on' => $note->written_on->format('Y-m-d'),
        'title' => $note->title,
        'comment' => 'Updated comment',
    ]);

    $response->assertRedirect(route('delivery-notes.show', $note));
    expect($note->fresh()->comment)->toBe('Updated comment');
});

test('update is forbidden on a finished note', function () {
    $user = deliveryNoteUser(['delivery-notes.update']);
    $note = ownDeliveryNote($user, ['status' => 'finished']);

    $response = $this->actingAs($user)->put(route('delivery-notes.update', $note), [
        'project_id' => $note->project_id,
        'written_on' => $note->written_on->format('Y-m-d'),
        'title' => $note->title,
        'comment' => 'Updated comment',
    ]);

    $response->assertForbidden();
});

test('updating a signed note reverts its status to new', function () {
    Storage::fake('local');
    $user = deliveryNoteUser(['delivery-notes.update']);
    $note = ownDeliveryNote($user, ['status' => 'signed']);

    $this->actingAs($user)->put(route('delivery-notes.update', $note), [
        'project_id' => $note->project_id,
        'written_on' => $note->written_on->format('Y-m-d'),
        'title' => $note->title,
        'comment' => 'Updated comment',
        // required when transitioning signed -> new, per DeliveryNoteUpdateRequest.
        'document' => UploadedFile::fake()->create('document.pdf', 10, 'application/pdf'),
    ]);

    expect($note->fresh()->status)->toBe('new');
});

// destroy

test('destroy removes a non-finished note', function () {
    $user = deliveryNoteUser(['delivery-notes.delete']);
    $note = ownDeliveryNote($user, ['status' => 'new']);

    $response = $this->actingAs($user)->delete(route('delivery-notes.destroy', $note));

    $response->assertRedirect(route('delivery-notes.index'));
    expect(DeliveryNote::find($note->id))->toBeNull();
});

// sign

test('sign is allowed on a new note and stores a signature', function () {
    Storage::fake('local');
    Event::fake([DeliveryNoteSignedEvent::class]);
    $user = deliveryNoteUser(['delivery-notes.get-signature']);
    $note = ownDeliveryNote($user, ['status' => 'new']);

    $this->actingAs($user)->post('/delivery-notes/'.$note->id.'/sign', [
        'signature' => DELIVERY_NOTE_TINY_PNG_BASE64,
    ]);

    expect($note->fresh()->status)->toBe('signed');
    Event::assertDispatched(DeliveryNoteSignedEvent::class);
});

test('sign is forbidden on an already signed note', function () {
    $user = deliveryNoteUser(['delivery-notes.get-signature']);
    $note = ownDeliveryNote($user, ['status' => 'signed']);

    $response = $this->actingAs($user)->post('/delivery-notes/'.$note->id.'/sign', [
        'signature' => DELIVERY_NOTE_TINY_PNG_BASE64,
    ]);

    $response->assertForbidden();
});

// finish

test('finish is allowed with approve permission', function () {
    $user = deliveryNoteUser(['delivery-notes.approve']);
    $note = ownDeliveryNote($user, ['status' => 'signed']);

    $response = $this->actingAs($user)->get(route('delivery-notes.finish', $note));

    $response->assertRedirect();
    expect($note->fresh()->status)->toBe('finished');
});

test('finish is forbidden without approve permission', function () {
    $user = deliveryNoteUser(['delivery-notes.update', 'delivery-notes.delete']);
    $note = ownDeliveryNote($user, ['status' => 'signed']);

    $response = $this->actingAs($user)->get(route('delivery-notes.finish', $note));

    $response->assertForbidden();
    expect($note->fresh()->status)->toBe('signed');
});

// activity log regression

test('finishing a note writes an activity log entry with the new attribute_changes shape', function () {
    $user = deliveryNoteUser(['delivery-notes.approve']);
    $note = ownDeliveryNote($user, ['status' => 'signed']);

    $this->actingAs($user)->get(route('delivery-notes.finish', $note));

    $activity = Activity::where('subject_type', DeliveryNote::class)
        ->where('subject_id', $note->id)
        ->latest('id')
        ->first();

    expect($activity)->not->toBeNull();
    expect($activity->attribute_changes['attributes']['status'] ?? null)->toBe('finished');
    expect($activity->attribute_changes['old']['status'] ?? null)->toBe('signed');
});

// email

test('email sends the delivery note mail', function () {
    Mail::fake();
    $user = deliveryNoteUser(['delivery-notes.email']);
    $note = ownDeliveryNote($user);

    $this->actingAs($user)->post(route('delivery-notes.email', $note), [
        'email_to' => [['email' => 'customer@example.com']],
    ]);

    Mail::assertQueued(DeliveryNoteMail::class);
});

// email signature request

test('emailSignatureRequest is allowed on a new note and sends the mail', function () {
    Mail::fake();
    $user = deliveryNoteUser(['delivery-notes.send-signature-request']);
    $note = ownDeliveryNote($user, ['status' => 'new']);

    $this->actingAs($user)->post('/delivery-notes/'.$note->id.'/email-signature-request', [
        'email' => 'customer@example.com',
    ]);

    Mail::assertQueued(DeliveryNoteSignatureRequestMail::class);
    expect($note->fresh()->signatureRequest)->not->toBeNull();
});

// email download request

test('emailDownloadRequest is allowed on a signed note and sends the mail', function () {
    Mail::fake();
    $user = deliveryNoteUser(['delivery-notes.send-download-request']);
    $note = ownDeliveryNote($user, ['status' => 'signed']);

    $this->actingAs($user)->post('/delivery-notes/'.$note->id.'/email-download-request', [
        'email' => 'customer@example.com',
    ]);

    Mail::assertQueued(DeliveryNoteDownloadRequestMail::class);
});

// download (real pdflatex)

test('download renders a real pdf for an authorized user', function () {
    Storage::fake('local');
    $user = deliveryNoteUser(['delivery-notes.createpdf']);
    $note = ownDeliveryNote($user, ['status' => 'signed']);
    // downloadPDF() only renders a fresh Latex PDF once a signature is actually attached
    // (without one it just serves the raw uploaded document instead), and the template
    // embeds that document via \includepdf, so both need to be real attached media.
    $note->addSignature(DELIVERY_NOTE_TINY_PNG_BASE64);
    $note->addDocument(realPdfUploadedFile());

    $response = $this->actingAs($user)->get(route('delivery-notes.download', $note));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'application/pdf');
})->group('pdflatex');

// customer-facing signed routes

test('customer sign form is shown for a valid signature request token', function () {
    $note = ownDeliveryNote(deliveryNoteUser(), ['status' => 'new']);
    $note->generateSignatureRequest();

    $response = $this->get(route('delivery-notes.customer-sign', $note->fresh()->signatureRequest->token));

    $response->assertSuccessful();
});

test('customer sign form warns on an invalid token', function () {
    $response = $this->get(route('delivery-notes.customer-sign', 'not-a-real-token'));

    $response->assertSuccessful();
    expect(session('warning'))->not->toBeNull();
});

test('customer sign stores a signature and generates a download request', function () {
    Storage::fake('local');
    Event::fake([DeliveryNoteSignedEvent::class]);
    $note = ownDeliveryNote(deliveryNoteUser(), ['status' => 'new']);
    $note->generateSignatureRequest();
    $token = $note->fresh()->signatureRequest->token;

    $response = $this->post(route('delivery-notes.customer-sign', $token), [
        'signature' => DELIVERY_NOTE_TINY_PNG_BASE64,
    ]);

    $response->assertSuccessful();
    expect($note->fresh()->status)->toBe('signed');
    expect($note->fresh()->downloadRequest)->not->toBeNull();
    Event::assertDispatched(DeliveryNoteSignedEvent::class);
});

test('customer download deletes the download request and streams a real pdf', function () {
    Storage::fake('local');
    $note = ownDeliveryNote(deliveryNoteUser(), ['status' => 'signed']);
    $note->addSignature(DELIVERY_NOTE_TINY_PNG_BASE64);
    $note->addDocument(realPdfUploadedFile());
    $note->generateDownloadRequest();
    $token = $note->fresh()->downloadRequest->token;

    $response = $this->get(route('delivery-notes.customer-download', $token));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'application/pdf');
    expect($note->fresh()->downloadRequest)->toBeNull();
})->group('pdflatex');

test('customer download warns on an invalid token instead of erroring', function () {
    $response = $this->get(route('delivery-notes.customer-download', 'not-a-real-token'));

    $response->assertSuccessful();
    expect(session('warning'))->not->toBeNull();
});

test('customer email download request queues the mail for a valid token', function () {
    Mail::fake();
    $note = ownDeliveryNote(deliveryNoteUser(), ['status' => 'signed']);
    $note->generateDownloadRequest();
    $token = $note->fresh()->downloadRequest->token;

    $response = $this->post(route('delivery-notes.customer-email-download-request', $token), [
        'email' => 'customer@example.com',
    ]);

    $response->assertSuccessful();
    Mail::assertQueued(DeliveryNoteDownloadRequestMail::class);
});

test('customer email download request warns on an invalid token instead of erroring', function () {
    Mail::fake();

    $response = $this->post(route('delivery-notes.customer-email-download-request', 'not-a-real-token'), [
        'email' => 'customer@example.com',
    ]);

    $response->assertSuccessful();
    expect(session('warning'))->not->toBeNull();
    Mail::assertNothingQueued();
});
