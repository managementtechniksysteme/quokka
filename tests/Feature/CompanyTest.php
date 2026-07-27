<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Person;
use App\Models\User;

function companyUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

// index

test('index is shown for a user with view permission', function () {
    $user = companyUser(['companies.view']);

    $response = $this->actingAs($user)->get(route('companies.index'));

    $response->assertSuccessful();
    $response->assertViewIs('company.index');
});

test('index is forbidden without view permission', function () {
    $user = companyUser();

    $response = $this->actingAs($user)->get(route('companies.index'));

    $response->assertForbidden();
});

// create / store

test('create form is shown for a user with create permission', function () {
    $user = companyUser(['companies.create']);

    $response = $this->actingAs($user)->get(route('companies.create'));

    $response->assertSuccessful();
    $response->assertViewIs('company.create');
});

test('store creates a company', function () {
    $user = companyUser(['companies.create']);

    $response = $this->actingAs($user)->post(route('companies.store'), [
        'name' => 'Acme Corp',
    ]);

    $company = Company::where('name', 'Acme Corp')->sole();

    $response->assertRedirect(route('companies.show', $company));
    expect($company->name)->toBe('Acme Corp');
    expect($company->created_at->eq($company->updated_at))->toBeTrue();
});

test('store links an unassigned contact person to the company', function () {
    $user = companyUser(['companies.create']);
    $person = Person::factory()->create();

    $response = $this->actingAs($user)->post(route('companies.store'), [
        'name' => 'Acme Corp',
        'contact_person_id' => $person->id,
    ]);

    $company = Company::where('name', 'Acme Corp')->sole();

    $response->assertRedirect(route('companies.show', $company));
    $response->assertSessionHas('success');
    expect($company->contact_person_id)->toBe($person->id);
    expect($person->fresh()->company_id)->toBe($company->id);
    expect($company->created_at->eq($company->updated_at))->toBeTrue();
});

test('store shows a warning when the contact person already belongs to another company', function () {
    $user = companyUser(['companies.create']);
    $existingCompany = Company::factory()->create();
    $person = Person::factory()->create(['company_id' => $existingCompany->id]);

    $response = $this->actingAs($user)->post(route('companies.store'), [
        'name' => 'Acme Corp',
        'contact_person_id' => $person->id,
    ]);

    $company = Company::where('name', 'Acme Corp')->sole();

    $response->assertRedirect(route('companies.show', $company));
    $response->assertSessionHas('warning');
    expect($company->contact_person_id)->toBeNull();
    expect($person->fresh()->company_id)->toBe($existingCompany->id);
    expect($company->created_at->eq($company->updated_at))->toBeTrue();
});

test('store is forbidden without create permission', function () {
    $user = companyUser();
    $countBefore = Company::count();

    $response = $this->actingAs($user)->post(route('companies.store'), [
        'name' => 'Acme Corp',
    ]);

    $response->assertForbidden();
    expect(Company::count())->toBe($countBefore);
});

// show

test('show tabs redirect to overview when the user lacks tab-specific permission', function () {
    $user = companyUser(['companies.view']);
    $company = Company::factory()->create();

    $response = $this->actingAs($user)->get(route('companies.show', [$company, 'tab' => 'projects']));

    $response->assertRedirect(route('companies.show', [$company, 'tab' => 'overview']));
});

test('show overview tab is allowed with view permission', function () {
    $user = companyUser(['companies.view']);
    $company = Company::factory()->create();

    $response = $this->actingAs($user)->get(route('companies.show', [$company, 'tab' => 'overview']));

    $response->assertSuccessful();
    $response->assertViewIs('company.show_tab_overview');
});

test('show is forbidden without view permission', function () {
    $user = companyUser();
    $company = Company::factory()->create();

    $response = $this->actingAs($user)->get(route('companies.show', $company));

    $response->assertForbidden();
});

// edit

test('edit is shown for a user with update permission', function () {
    $user = companyUser(['companies.update']);
    $company = Company::factory()->create();

    $response = $this->actingAs($user)->get(route('companies.edit', $company));

    $response->assertSuccessful();
    $response->assertViewIs('company.edit');
});

// update

test('update persists changes', function () {
    $user = companyUser(['companies.update']);
    $company = Company::factory()->create();

    $response = $this->actingAs($user)->put(route('companies.update', $company), [
        'name' => 'Updated name',
    ]);

    $response->assertRedirect(route('companies.show', $company));
    expect($company->fresh()->name)->toBe('Updated name');
});

test('update is forbidden without update permission', function () {
    $user = companyUser();
    $company = Company::factory()->create();

    $response = $this->actingAs($user)->put(route('companies.update', $company), [
        'name' => 'Updated name',
    ]);

    $response->assertForbidden();
});

// destroy

test('destroy removes a company', function () {
    $user = companyUser(['companies.delete']);
    $company = Company::factory()->create();

    $response = $this->actingAs($user)->delete(route('companies.destroy', $company));

    $response->assertRedirect(route('companies.index'));
    expect(Company::find($company->id))->toBeNull();
});

test('destroy is forbidden without delete permission', function () {
    $user = companyUser();
    $company = Company::factory()->create();

    $response = $this->actingAs($user)->delete(route('companies.destroy', $company));

    $response->assertForbidden();
    expect(Company::find($company->id))->not->toBeNull();
});
