<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\WebpushTestNotification;
use Illuminate\Support\Facades\Notification;

test('store creates a push subscription for the acting user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson(route('webpush.store'), [
        'endpoint' => 'https://push.example.com/abc',
        'public_key' => 'public-key',
        'auth_token' => 'auth-token',
    ]);

    $response->assertOk();
    $response->assertJson(['success' => true]);
    $this->assertDatabaseHas('push_subscriptions', [
        'subscribable_id' => $user->employee_id,
        'subscribable_type' => User::class,
        'endpoint' => 'https://push.example.com/abc',
    ]);
});

test('store validates required fields', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson(route('webpush.store'), []);

    $response->assertJsonValidationErrors(['endpoint', 'public_key', 'auth_token']);
});

test('destroy removes the acting user\'s push subscription by endpoint', function () {
    $user = User::factory()->create();
    $user->updatePushSubscription('https://push.example.com/abc', 'key', 'token', 'aesgcm');

    $response = $this->actingAs($user)->deleteJson(route('webpush.destroy'), [
        'endpoint' => 'https://push.example.com/abc',
    ]);

    $response->assertNoContent();
    $this->assertDatabaseMissing('push_subscriptions', [
        'subscribable_id' => $user->employee_id,
        'endpoint' => 'https://push.example.com/abc',
    ]);
});

test('test sends a webpush test notification to the acting user', function () {
    Notification::fake();
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('webpush.test'));

    $response->assertRedirect(route('user-settings.edit', ['tab' => 'notifications']));
    Notification::assertSentTo($user, WebpushTestNotification::class);
});
