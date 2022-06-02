<?php

namespace App\Http\Controllers;

use App\Events\HolidayAllowanceAdjustedEvent;
use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Http\Requests\PermissionsUpdateRequest;
use App\Models\ApplicationSettings;
use App\Models\Employee;
use App\Models\User;
use App\Models\UserSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    protected function resourceAbilityMap()
    {
        return array_merge(parent::resourceAbilityMap(), [
            'showEmail' => 'email',
            'email' => 'email',
            'download' => 'createPdf',
            'impersonate' => 'impersonate',
        ]);
    }

    public function __construct()
    {
        $this->authorizeResource(Employee::class, 'employee');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $employees = Employee::filterSearch($request->search)
            ->with('person')
            ->paginate(Auth::user()->settings->list_pagination_size)
            ->appends($request->except('page'));

        return view('employee.index')->with(compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $people = ApplicationSettings::get()->company->people()->doesntHave('employee')->order()->get();
        $holidaysSteps = ApplicationSettings::get()->accounting_min_amount;

        return view('employee.create')
            ->with('employee', null)
            ->with('currentPerson', null)
            ->with('people', $people->toJson())
            ->with(compact('holidaysSteps'))
            ->with('currentAvatarColour', null)
            ->with('avatarColours', json_encode(UserSettings::avatarColours));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeStoreRequest $request)
    {
        $validatedData = $request->validated();

        $employee = Employee::create($validatedData);

        if ($request->filled('username') && $request->filled('password')) {
            $this->createUser(
                $employee,
                $request->username,
                $request->password,
                UserSettings::avatarColourFromHex($request->avatar_colour)['label']
            );
        }

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Der Mitarbeiter wurde erfolgreich angelegt.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        $employee->load('person')->load('user.settings');

        return view('employee.show')->with(compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Employee $employee)
    {
        $employee->load('person')->load('user.settings')->load('user.permissions');
        $holidaysSteps = ApplicationSettings::get()->accounting_min_amount;

        $currentPerson = $employee->person;
        $people = ApplicationSettings::get()->company->people()->doesntHave('employee')->order()->get();

        $currentAvatarColour =
            optional($employee->user)->settings ?
                UserSettings::avatarColourFromName($employee->user->settings->avatar_colour) :
                null;

        return view('employee.edit')
            ->with('employee', $employee)
            ->with('currentPerson', $currentPerson->toJson())
            ->with('people', $people->toJson())
            ->with(compact('holidaysSteps'))
            ->with('currentAvatarColour', json_encode($currentAvatarColour))
            ->with('avatarColours', json_encode(UserSettings::avatarColours));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(EmployeeUpdateRequest $request, Employee $employee)
    {
        $validatedData = $request->validated();

        $oldHolidayAllowance = $employee->holidays;

        $employee->update($validatedData);

        $user = $employee->user;

        if (! $user && $request->filled('username') && $request->filled('password')) {
            $this->createUser(
                $employee,
                $request->username,
                $request->password,
                UserSettings::avatarColourFromHex($request->avatar_colour)['label']
            );
        } else {
            if ($request->filled('username')) {
                $user->update([
                    'username' => $request->username,
                ]);
            }

            if ($request->filled('password')) {
                $user->update([
                    'password' => Hash::make($request->password),
                    config('auth2fa.otp_secret_column') => null,
                ]);

                $currentUser = Auth::user();

                Auth::setUser($user);
                Auth::logoutOtherDevices($request->password);

                Auth::setUser($currentUser);
            }

            $user->settings->update([
                'avatar_colour' => UserSettings::avatarColourFromHex($request->avatar_colour)['label'],
            ]);
        }

        if($employee->holidays !== $oldHolidayAllowance) {
            event(new HolidayAllowanceAdjustedEvent($employee, $oldHolidayAllowance, $employee->holidays, true));
        }

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Der Mitarbeiter wurde erfolgreich bearbeitet.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $user = $employee->user;
        $settings = $employee->user->settings;

        if ($settings) {
            $settings->delete();
        }

        if ($user) {
            $user->delete();
        }

        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Der Mitarbeiter wurde erfolgreich entfernt.');
    }

    private function createUser($employee, $username, $password, $avatarColour)
    {
        $user = User::create([
            'employee_id' => $employee->person_id,
            'username' => $username,
            'password' => Hash::make($password),
        ]);

        UserSettings::create([
            'user_id' => $user->employee_id,
            'avatar_colour' => $avatarColour,
        ]);
    }

    public function grantAccess(Request $request, Employee $employee)
    {
        $employee->user->restore();

        return redirect()->route('employees.index')->with('success', 'Der Zugang wurde erfolgreich entsperrt.');
    }

    public function denyAccess(Request $request, Employee $employee)
    {
        $employee->user->delete();

        return redirect()->route('employees.index')->with('success', 'Der Zugang wurde erfolgreich gesperrt.');
    }

    public function editPermissions(Employee $employee)
    {
        $employee->load('person')->load('user.permissions');

        $roles = Role::orderBy('name')->get();

        return view('employee.edit_permissions')
            ->with(compact('employee'))
            ->with(compact('roles'))
            ->with('currentRole', null);
    }

    public function updatePermissions(PermissionsUpdateRequest $request, Employee $employee)
    {
        $user = $employee->user;

        $validatedData = $request->validated();

        if(isset($validatedData['role_id'])) {
            $permissions = Role::find($validatedData['role_id'])->permissions;

            $employee->user->syncPermissions($permissions);

            return redirect()
                ->route('employees.edit-permissions', $employee)
                ->with('success', 'Die Berechtigungen der Rolle wurden erfolgreich zugewiesen.');
        }

        foreach (Permission::select('name')->pluck('name') as $permission) {
            $permissionField = str_replace('.', '_', $permission);

            if(isset($validatedData[$permissionField])) {
                $user->givePermissionTo($permission);
            }
            else {
                $user->revokePermissionTo($permission);
            }
        }

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Die Berechtigungen wurden erfolgreich bearbeitet.');
    }

    public function impersonate(Employee $employee)
    {
        if(Auth::id() === $employee->person_id) {
            $userId = Session::pull('impersonatorId');

            Auth::loginUsingId($userId);

            return redirect()->route('home')->with('success', 'Du bist jetzt wieder mit deinem Benutzer angemeldet.');
        }

        if(!Session::has('impersonatorId')) {
            Session::put('impersonatorId', Auth::id());
        }

        Auth::loginUsingId($employee->person_id);

        return redirect()->route('home')->with('success', 'Du bist jetzt als dieser Benutzer angemeldet.');
    }
}
