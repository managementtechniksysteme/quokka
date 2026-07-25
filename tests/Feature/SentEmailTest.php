<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Mail;

function sendTestEmail(string $subject, string $to = 'someone@example.com'): void
{
    Mail::raw('Test body', function ($message) use ($subject, $to) {
        $message->to($to)->subject($subject);
    });
}

test('index is forbidden without tools.viewsentemails permission', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('sent-emails.index'));

    $response->assertForbidden();
});

test('index lists activities logged for sent emails', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tools.viewsentemails');

    sendTestEmail('Hello there');

    $response = $this->actingAs($user)->get(route('sent-emails.index'));

    $response->assertSuccessful();
    $response->assertViewIs('sent_email.index');
    $response->assertSee('Hello there');
});

test('index filters activities by search term', function () {
    $user = User::factory()->create();
    grantPermission($user, 'tools.viewsentemails');

    sendTestEmail('Findable Subject');
    sendTestEmail('Something else entirely');

    $response = $this->actingAs($user)->get(route('sent-emails.index', ['search' => 'Findable']));

    $response->assertSuccessful();
    $response->assertSee('Findable Subject');
    $response->assertDontSee('Something else entirely');
});
