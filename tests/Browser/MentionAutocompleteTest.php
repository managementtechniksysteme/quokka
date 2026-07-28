<?php

namespace Tests\Browser;

use App\Models\User;

function mentionAutocompleteUser(): User
{
    $user = User::factory()->create();

    grantPermission($user, 'notes.create');

    return $user;
}

test('typing @ lists a real employee and inserts a mention chip on selection', function () {
    $user = mentionAutocompleteUser();
    $target = User::factory()->create(['username' => 'jamiedoe']);
    $target->employee->person->update(['first_name' => 'Jamie', 'last_name' => 'Doe']);

    $this->actingAs($user);

    $page = visit(route('notes.create'));

    $page->click('.CodeMirror')
        ->typeSlowly('.CodeMirror textarea', '@jamie')
        ->assertVisible('.CodeMirror-hint')
        ->assertSeeIn('.CodeMirror-hint', 'Jamie Doe')
        ->click('.CodeMirror-hint')
        ->assertVisible('.q-mention-chip')
        ->assertSeeIn('.q-mention-chip', 'Jamie Doe');
});

// Regression for 3580b38: the vendor show-hint widget hardcodes color:black
// on .CodeMirror-hint, which used to leave dropdown text unreadable against
// the dark-mode background. Covers both @mention and #crossref triggers,
// since they render through the same shared .CodeMirror-hints CSS rule.
test('the autocomplete dropdown text is legible in dark mode', function () {
    $user = mentionAutocompleteUser();
    $target = User::factory()->create(['username' => 'jamiedoe']);
    $target->employee->person->update(['first_name' => 'Jamie', 'last_name' => 'Doe']);

    $this->actingAs($user);

    $page = visit(route('notes.create'))->inDarkMode();

    $page->click('.CodeMirror')
        ->typeSlowly('.CodeMirror textarea', '@jamie')
        ->assertVisible('.CodeMirror-hint')
        ->assertScript(
            "getComputedStyle(document.querySelector('.CodeMirror-hint')).color !== 'rgb(0, 0, 0)'",
            true
        );
});
