<?php

namespace Tests\Browser;

use App\Events\DeliveryNoteSignedEvent;
use App\Models\DeliveryNote;
use Illuminate\Support\Facades\Event;

// Regression for 2cbb1de: the pad used to render at a clipped, fixed 150px
// height. The public customer-sign page needs no authentication (token-based),
// so no user/permission setup is required here.
//
// pest-plugin-browser's drag() only drags one locator onto another (element
// centre to element centre), which can't draw a freehand stroke on a single
// canvas -- so the stroke is simulated by dispatching real PointerEvents
// directly at canvas coordinates, matching the pointerdown/pointermove/
// pointerup sequence signature_pad itself listens for.
test('the signature pad is not clipped and persists a drawn signature on submit', function () {
    Event::fake([DeliveryNoteSignedEvent::class]);

    $deliveryNote = DeliveryNote::factory()->create();
    $deliveryNote->generateSignatureRequest();
    $token = $deliveryNote->fresh()->signatureRequest->token;

    $page = visit(route('delivery-notes.customer-sign', $token));

    $page->assertScript("document.querySelector('.q-signpad canvas').getBoundingClientRect().height > 200", true)
        ->assertScript(<<<'JS'
            (function () {
                var canvas = document.querySelector('.q-signpad canvas');
                var rect = canvas.getBoundingClientRect();
                function point(type, x, y) {
                    return new PointerEvent(type, {
                        clientX: rect.left + x, clientY: rect.top + y,
                        bubbles: true, cancelable: true,
                        pointerId: 1, isPrimary: true, button: 0, buttons: 1,
                    });
                }
                canvas.dispatchEvent(point('pointerdown', 20, 20));
                canvas.dispatchEvent(point('pointermove', 60, 60));
                canvas.dispatchEvent(point('pointermove', 100, 30));
                document.dispatchEvent(point('pointerup', 100, 30));
                return true;
            })()
            JS,
            true
        )
        ->wait(0.3)
        ->assertScript("document.querySelector('#signature') !== null && document.querySelector('#signature').value.length > 0", true)
        ->click('button[type="submit"]')
        ->assertSee('erfolgreich unterschrieben');

    $deliveryNote->refresh();
    expect($deliveryNote->status)->toBe('signed');
    expect($deliveryNote->getFirstMedia('signature'))->not->toBeNull();
});
