<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SelectOptionCollection;
use App\Http\Resources\UserResource;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function index()
    {

    }

    public function store()
    {

    }

    public function update()
    {

    }

    public function destroy()
    {

    }

    public function selectOptions() {
        $vehicles = Vehicle::order()->get(['id', 'registration_identifier as text']);

        return new SelectOptionCollection($vehicles);
    }
}
