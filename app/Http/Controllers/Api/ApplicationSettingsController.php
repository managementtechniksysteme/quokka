<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationSettingsResource;
use App\Http\Resources\SelectOptionCollection;
use App\Http\Resources\UserResource;
use App\Models\ApplicationSettings;
use App\Models\Service;
use App\Models\ServiceReport;
use App\Models\Vehicle;
use App\Models\WageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ApplicationSettingsController extends Controller
{
    public function index()
    {
        $applicationSettings = ApplicationSettings::get();

        return new ApplicationSettingsResource($applicationSettings);
    }
}
