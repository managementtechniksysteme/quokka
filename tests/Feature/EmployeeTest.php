<?php

namespace Tests\Feature;

use App\Events\HolidayAllowanceAdjustedEvent;
use App\Models\ApplicationSettings;
use App\Models\Employee;
use App\Models\Person;
use App\Models\User;
use Illuminate\Support\Facades\Event;

function employeeUser(array $permissions = []): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        grantPermission($user, $permission);
    }

    return $user;
}

function setAccountingMinAmount(float $amount = 0.25): void
{
    ApplicationSettings::get()->update(['accounting_min_amount' => $amount]);
    ApplicationSettings::refreshCache();
}

// index

test('index is shown for a user with view permission', function () {
    $user = employeeUser(['employees.view']);

    $response = $this->actingAs($user)->get(route('employees.index'));

    $response->assertSuccessful();
    $response->assertViewIs('employee.index');
});

test('index is forbidden without view permission', function () {
    $user = employeeUser();

    $response = $this->actingAs($user)->get(route('employees.index'));

    $response->assertForbidden();
});

// create / store

test('create form is shown for a user with create permission', function () {
    $user = employeeUser(['employees.create']);

    $response = $this->actingAs($user)->get(route('employees.create'));

    $response->assertSuccessful();
    $response->assertViewIs('employee.create');
});

test('store creates an employee', function () {
    setAccountingMinAmount();
    $user = employeeUser(['employees.create']);
    $person = Person::factory()->create();

    $response = $this->actingAs($user)->post(route('employees.store'), [
        'person_id' => $person->id,
        'entered_on' => '2026-01-01',
        'holidays' => 0.5,
    ]);

    $employee = Employee::where('person_id', $person->id)->sole();

    $response->assertRedirect(route('employees.show', $employee));
});

test('store is forbidden without create permission', function () {
    setAccountingMinAmount();
    $user = employeeUser();
    $person = Person::factory()->create();
    $countBefore = Employee::count();

    $response = $this->actingAs($user)->post(route('employees.store'), [
        'person_id' => $person->id,
        'entered_on' => '2026-01-01',
        'holidays' => 0.5,
    ]);

    $response->assertForbidden();
    expect(Employee::count())->toBe($countBefore);
});

// show

test('show is allowed with view permission', function () {
    $user = employeeUser(['employees.view']);
    $employee = Employee::factory()->create();

    $response = $this->actingAs($user)->get(route('employees.show', $employee));

    $response->assertSuccessful();
    $response->assertViewIs('employee.show');
});

test('show is forbidden without view permission', function () {
    $user = employeeUser();
    $employee = Employee::factory()->create();

    $response = $this->actingAs($user)->get(route('employees.show', $employee));

    $response->assertForbidden();
});

// edit

test('edit is shown for a user with update permission', function () {
    $user = employeeUser(['employees.update']);
    $employee = Employee::factory()->create();

    $response = $this->actingAs($user)->get(route('employees.edit', $employee));

    $response->assertSuccessful();
    $response->assertViewIs('employee.edit');
});

// update

test('update persists changes', function () {
    setAccountingMinAmount();
    $user = employeeUser(['employees.update']);
    $employee = Employee::factory()->create(['holidays' => 5]);

    $response = $this->actingAs($user)->put(route('employees.update', $employee), [
        'person_id' => $employee->person_id,
        'entered_on' => $employee->entered_on->toDateString(),
        'holidays' => 5,
        'comment' => 'Updated comment',
    ]);

    $response->assertRedirect(route('employees.show', $employee));
});

test('update dispatches a holiday allowance adjusted event when holidays change', function () {
    Event::fake([HolidayAllowanceAdjustedEvent::class]);
    setAccountingMinAmount();
    $user = employeeUser(['employees.update']);
    $employee = Employee::factory()->create(['holidays' => 5]);

    $this->actingAs($user)->put(route('employees.update', $employee), [
        'person_id' => $employee->person_id,
        'entered_on' => $employee->entered_on->toDateString(),
        'holidays' => 10,
    ]);

    Event::assertDispatched(HolidayAllowanceAdjustedEvent::class);
});

test('update is forbidden without update permission', function () {
    setAccountingMinAmount();
    $user = employeeUser();
    $employee = Employee::factory()->create();

    $response = $this->actingAs($user)->put(route('employees.update', $employee), [
        'person_id' => $employee->person_id,
        'entered_on' => $employee->entered_on->toDateString(),
        'holidays' => $employee->holidays,
    ]);

    $response->assertForbidden();
});

// destroy

test('destroy removes an employee', function () {
    $user = employeeUser(['employees.delete']);
    $employee = Employee::factory()->create();

    $response = $this->actingAs($user)->delete(route('employees.destroy', $employee));

    $response->assertRedirect(route('employees.index'));
    expect(Employee::find($employee->person_id))->toBeNull();
});

test('destroy is forbidden without delete permission', function () {
    $user = employeeUser();
    $employee = Employee::factory()->create();

    $response = $this->actingAs($user)->delete(route('employees.destroy', $employee));

    $response->assertForbidden();
    expect(Employee::find($employee->person_id))->not->toBeNull();
});

// grantAccess / denyAccess - authorization regression (previously zero authorization at all)

test('grantAccess is allowed with update permission', function () {
    $user = employeeUser(['employees.update']);
    $employee = Employee::factory()->create();
    $employeeUserAccount = User::factory()->create(['employee_id' => $employee->person_id]);
    $employeeUserAccount->delete();

    $response = $this->actingAs($user)->get(route('employees.access-grant', $employee));

    $response->assertRedirect(route('employees.index'));
    expect(User::find($employeeUserAccount->employee_id))->not->toBeNull();
});

test('grantAccess is forbidden without update permission', function () {
    $user = employeeUser();
    $employee = Employee::factory()->create();
    $employeeUserAccount = User::factory()->create(['employee_id' => $employee->person_id]);
    $employeeUserAccount->delete();

    $response = $this->actingAs($user)->get(route('employees.access-grant', $employee));

    $response->assertForbidden();
});

test('denyAccess is allowed with update permission', function () {
    $user = employeeUser(['employees.update']);
    $employee = Employee::factory()->create();
    User::factory()->create(['employee_id' => $employee->person_id]);

    $response = $this->actingAs($user)->get(route('employees.access-deny', $employee));

    $response->assertRedirect(route('employees.index'));
    expect(User::find($employee->person_id))->toBeNull();
});

test('denyAccess is forbidden without update permission', function () {
    $user = employeeUser();
    $employee = Employee::factory()->create();
    User::factory()->create(['employee_id' => $employee->person_id]);

    $response = $this->actingAs($user)->get(route('employees.access-deny', $employee));

    $response->assertForbidden();
});

// editPermissions / updatePermissions - authorization regression, and the actual permission-escalation fix

test('editPermissions is allowed with update permission', function () {
    // employee.edit_permissions renders the same hand-written full-catalog checkbox
    // partial as role/create and role/edit - see the note in RoleTest.php.
    $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
    $user = employeeUser(['employees.update']);
    $employee = Employee::factory()->create();
    User::factory()->create(['employee_id' => $employee->person_id]);

    $response = $this->actingAs($user)->get(route('employees.edit-permissions', $employee));

    $response->assertSuccessful();
    $response->assertViewIs('employee.edit_permissions');
});

test('editPermissions is forbidden without update permission', function () {
    $user = employeeUser();
    $employee = Employee::factory()->create();
    User::factory()->create(['employee_id' => $employee->person_id]);

    $response = $this->actingAs($user)->get(route('employees.edit-permissions', $employee));

    $response->assertForbidden();
});

test('updatePermissions assigns individual permissions with update permission', function () {
    $user = employeeUser(['employees.update']);
    grantPermission($user, 'vehicles.view');
    $employee = Employee::factory()->create();
    $targetUser = User::factory()->create(['employee_id' => $employee->person_id]);

    $this->actingAs($user)->patch(route('employees.update-permissions', $employee), [
        'vehicles_view' => 1,
    ]);

    expect($targetUser->fresh()->hasPermissionTo('vehicles.view'))->toBeTrue();
});

test('updatePermissions is forbidden without update permission - the privilege escalation regression', function () {
    \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'vehicles.view']);
    $user = employeeUser();
    $employee = Employee::factory()->create();
    $targetUser = User::factory()->create(['employee_id' => $employee->person_id]);

    $response = $this->actingAs($user)->patch(route('employees.update-permissions', $employee), [
        'vehicles_view' => 1,
    ]);

    $response->assertForbidden();
    expect($targetUser->fresh()->hasPermissionTo('vehicles.view'))->toBeFalse();
});

// impersonate

test('impersonate is allowed for another employee with impersonate permission', function () {
    $user = employeeUser(['employees.impersonate']);
    $employee = Employee::factory()->create();
    User::factory()->create(['employee_id' => $employee->person_id]);

    $response = $this->actingAs($user)->get(route('employees.impersonate', $employee));

    $response->assertRedirect(route('home'));
    expect(auth()->id())->toBe($employee->person_id);
});

test('impersonate is forbidden without impersonate permission', function () {
    $user = employeeUser();
    $employee = Employee::factory()->create();
    User::factory()->create(['employee_id' => $employee->person_id]);

    $response = $this->actingAs($user)->get(route('employees.impersonate', $employee));

    $response->assertForbidden();
});
