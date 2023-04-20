<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SelectOptionCollection;
use App\Http\Resources\UserResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    protected function resourceAbilityMap()
    {
        return array_merge(parent::resourceAbilityMap(), [
            'showEmail' => 'email',
            'email' => 'email',
            'download' => 'createPdf',
        ]);
    }

    public function resourceMethodsWithoutModels()
    {
        return array_merge(parent::resourceMethodsWithoutModels(), ['downloadList']);
    }

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
        $projects = Project::order()->get(['id', 'name as text']);

        return new SelectOptionCollection($projects);
    }
}
