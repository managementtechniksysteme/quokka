<?php

namespace App\Http\Controllers;

use App\Http\Requests\MaterialServiceStoreRequest;
use App\Http\Requests\MaterialServiceUpdateRequest;
use App\Models\MaterialService;
use App\Models\WageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialServiceController extends Controller
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
        $this->authorizeResource(MaterialService::class, 'material_service');
    }

    public function index(Request $request)
    {
        $materialServices = MaterialService::filterSearch($request->search)
            ->order($request->sort)
            ->paginate(Auth::user()->settings->list_pagination_size)
            ->appends($request->except('page'));

        $materialServicesCount = MaterialService::count();
        $wageServicesCount = WageService::count();

        return view('service.index_tab_material_services')
            ->with(compact('materialServices'))
            ->with(compact('materialServicesCount'))
            ->with(compact('wageServicesCount'))
            ->with('tab', 'material-services');
    }

    public function create()
    {
        return view('material_service.create')->with('materialService', null);
    }

    public function store(MaterialServiceStoreRequest $request)
    {
        $materialService = MaterialService::create($request->validated());

        return redirect()->route('material-services.show', $materialService)->with('success', 'Die Materialleistung wurde erfolgreich angelegt.');
    }

    public function show(MaterialService $materialService)
    {
        return view('material_service.show')->with(compact('materialService'));
    }

    public function edit(MaterialService $materialService)
    {
        return view('material_service.edit')->with(compact('materialService'));
    }

    public function update(MaterialServiceUpdateRequest $request, MaterialService $materialService)
    {
        $materialService->update($request->validated());

        return redirect()->route('material-services.show', $materialService)->with('success', 'Die Materialleistung wurde erfolgreich bearbeitet.');
    }

    public function destroy(MaterialService $materialService)
    {
        $materialService->delete();

        return redirect()->route('material-services.index')->with('success', 'Die Materialleistung wurde erfolgreich entfernt.');
    }
}
