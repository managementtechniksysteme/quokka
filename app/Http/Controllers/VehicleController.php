<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehicleStoreRequest;
use App\Http\Requests\VehicleUpdateRequest;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $vehicles = Vehicle::filterSearch($request->input())
            ->with('logbook')
            ->order($request->input())
            ->paginate(Auth::user()->settings->list_pagination_size)
            ->appends($request->except('page'));

        return view('vehicle.index')
            ->with(compact('vehicles'));
    }

    public function create()
    {
        return view('vehicle.create')->with('vehicle', null);
    }

    public function store(VehicleStoreRequest $request)
    {
        $vehicle = Vehicle::create($request->validated());

        return redirect()->route('vehicles.show', $vehicle)->with('success', 'Das Fahrzeug wurde erfolgreich angelegt.');
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load('logbook');

        return view('vehicle.show')->with('vehicle', $vehicle);
    }

    public function edit(Vehicle $vehicle)
    {
        return view('vehicle.edit')->with('vehicle', $vehicle);
    }

    public function update(VehicleUpdateRequest $request, Vehicle $vehicle)
    {
        $vehicle = $vehicle->update($request->validated());

        return redirect()->route('vehicles.show', $vehicle)->with('success', 'Das Fahrzeug wurde erfolgreich bearbeitet.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()->route('vehicles.index')->with('success', 'Das Fahrzeug wurde erfolgreich entfernt.');
    }
}
