<?php

namespace Tests\Browser;

use App\Models\Person;
use App\Models\User;

function crossReferenceAutocompleteUser(): User
{
    $user = User::factory()->create();

    grantPermission($user, 'notes.create');
    grantPermission($user, 'search');
    grantPermission($user, 'people.view');

    return $user;
}

test('typing # lists a matching record and inserts a cross-reference chip on selection', function () {
    $user = crossReferenceAutocompleteUser();
    Person::factory()->create(['first_name' => 'Zephyrina', 'last_name' => 'Quokkard']);

    $this->actingAs($user);

    $page = visit(route('notes.create'));

    $page->click('.CodeMirror')
        ->typeSlowly('.CodeMirror textarea', '#Zephyrina')
        ->assertVisible('.CodeMirror-hint')
        ->assertSeeIn('.CodeMirror-hint', 'Zephyrina Quokkard')
        ->click('.CodeMirror-hint')
        ->assertVisible('.q-crossref-chip')
        ->assertSeeIn('.q-crossref-chip', 'Zephyrina Quokkard');
});

// Regression for f7d3a20: each keystroke fires its own /cross-references
// request; without the AbortController cancelling the superseded one, results
// could resolve out of order and leave a stale suggestion on screen.
test('a superseded cross-reference lookup does not leave stale results on screen', function () {
    $user = crossReferenceAutocompleteUser();
    Person::factory()->create(['first_name' => 'Alphonso', 'last_name' => 'Testperson']);
    Person::factory()->create(['first_name' => 'Omeganna', 'last_name' => 'Testperson']);

    $this->actingAs($user);

    $page = visit(route('notes.create'));

    $page->click('.CodeMirror')
        ->typeSlowly('.CodeMirror textarea', '#Alphonso')
        ->keys('.CodeMirror textarea', array_fill(0, 8, 'Backspace'))
        ->typeSlowly('.CodeMirror textarea', 'Omeganna')
        ->wait(1)
        ->assertVisible('.CodeMirror-hint')
        ->assertSeeIn('.CodeMirror-hints', 'Omeganna Testperson')
        ->assertDontSeeIn('.CodeMirror-hints', 'Alphonso Testperson');
});
