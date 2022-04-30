<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    protected function resourceAbilityMap()
    {
        return array_merge(parent::resourceAbilityMap(), [
            'showEmail' => 'email',
            'email' => 'email',
            'download' => 'createPdf',
        ]);
    }

    public function __construct()
    {
        $this->authorizeResource(Role::class, 'role');
    }

    public function index(Request $request)
    {
        $roles = Role::orderBy('name')->withCount('permissions')
            ->when($request->has('search'), function ($query) use ($request) {
                return $query->where('name', 'LIKE', "%{$request->search}%");
            })
            ->paginate(Auth::user()->settings->list_pagination_size)
            ->appends($request->except('page'));

        return view('role.index')->with(compact('roles'));
    }

    public function create()
    {
        return view('role.create')->with('role', null);
    }

    public function store(RoleStoreRequest $request)
    {
        $validatedData = $request->validated();

        $role = Role::create(['name' => $validatedData['name']]);

        foreach (Permission::select('name')->pluck('name') as $permission) {
            $permissionField = str_replace('.', '_', $permission);

            if(isset($validatedData[$permissionField])) {
                $role->givePermissionTo($permission);
            }
        }

        return redirect()->route('roles.show', $role)->with('success', 'Die Rolle wurde erfolgreich angelegt.');
    }

    public function show(Role $role)
    {
        $role->load('permissions')->loadCount('permissions');

        return view('role.show')->with(compact('role'));
    }

    public function edit(Role $role)
    {
        $role->load('permissions');

        return view('role.edit')->with(compact('role'));
    }

    public function update(RoleUpdateRequest $request, Role $role)
    {
        $validatedData = $request->validated();

        foreach (Permission::select('name')->pluck('name') as $permission) {
            $permissionField = str_replace('.', '_', $permission);

            if(isset($validatedData[$permissionField])) {
                $role->givePermissionTo($permission);
            }
            else {
                $role->revokePermissionTo($permission);
            }
        }

        return redirect()->route('roles.show', $role)->with('success', 'Die Rolle wurde erfolgreich bearbeitet.');
    }

    public function destroy(Role $role)
    {
        $role->revokePermissionTo(Permission::all());
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Die Rolle wurde erfolgreich entfernt.');
    }
}
