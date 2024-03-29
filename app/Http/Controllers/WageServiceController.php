<?php

namespace App\Http\Controllers;

use App\Http\Requests\WageServiceStoreRequest;
use App\Http\Requests\WageServiceUpdateRequest;
use App\Models\ApplicationSettings;
use App\Models\MaterialService;
use App\Models\WageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WageServiceController extends Controller
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
        $this->authorizeResource(WageService::class, 'wage_service');
    }

    public function index(Request $request)
    {
        $wageServices = WageService::filterSearch($request->search)
            ->order($request->sort)
            ->paginate(Auth::user()->settings->list_pagination_size)
            ->appends($request->except('page'));

        $materialServicesCount = MaterialService::count();
        $wageServicesCount = WageService::count();

        return view('service.index_tab_wage_services')
            ->with(compact('wageServices'))
            ->with(compact('materialServicesCount'))
            ->with(compact('wageServicesCount'))
            ->with('tab', 'wage-services');
    }

    public function create()
    {
        $units = WageService::distinct('unit')->pluck('unit');

        return view('wage_service.create')
            ->with('wageService', null)
            ->with('units', $units->toJson())
            ->with('currentUnit', '');
    }

    public function store(WageServiceStoreRequest $request)
    {
        $wageService = WageService::create($request->validated());

        return redirect()->route('wage-services.show', $wageService)->with('success', 'Die Lohndienstleistung wurde erfolgreich angelegt.');
    }

    public function show(WageService $wageService)
    {
        $currencyUnit = ApplicationSettings::get()->currency_unit;

        return view('wage_service.show')
            ->with(compact('wageService'))
            ->with(compact('currencyUnit'));
    }

    public function edit(WageService $wageService)
    {
        $units = WageService::distinct('unit')->pluck('unit');

        return view('wage_service.edit')
            ->with(compact('wageService'))
            ->with('units', $units->toJson())
            ->with('currentUnit', $wageService->unit);
    }

    public function update(WageServiceUpdateRequest $request, WageService $wageService)
    {
        $wageService->update($request->validated());

        return redirect()->route('wage-services.show', $wageService)->with('success', 'Die Lohndienstleistung wurde erfolgreich bearbeitet.');
    }

    public function destroy(WageService $wageService)
    {
        $wageService->delete();

        return redirect()->route('wage-services.index')->with('success', 'Die Lohndienstleistung wurde erfolgreich entfernt.');
    }
}
