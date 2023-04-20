<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SelectOptionCollection;
use App\Http\Resources\ServiceTypeCollection;
use App\Http\Resources\UserResource;
use App\Models\ApplicationSettings;
use App\Models\Service;
use App\Models\ServiceReport;
use App\Models\Vehicle;
use App\Models\WageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
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

    public function hourlyBasedIds() {
        $hourlyBasedServiceIds = WageService::where('unit', ApplicationSettings::get()->services_hour_unit)->pluck('id');

        return new JsonResponse(['data' => ['ids' => $hourlyBasedServiceIds]]);
    }

    public function selectOptions() {
        $services = Service::order()->get()->each(function ($service) {
            $service->text = $service->name_with_unit;
        });

        return new SelectOptionCollection($services);
    }

    public function types() {
        $services = Service::select(['id', 'type'])->get();

        return new ServiceTypeCollection($services);
    }
}
