<?php

namespace App\Http\Controllers;

use App\Http\Requests\WageServiceStoreRequest;
use App\Http\Requests\WageServiceUpdateRequest;
use App\Models\MaterialService;
use App\Models\WageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WageServiceController extends Controller
{
    public function index(Request $request)
    {
        $wageServices = WageService::filter($request->input())
            ->order($request->input())
            ->paginate(Auth::user()->settings->list_pagination_size)
            ->appends($request->except('page'));

        $materialServicesCount = MaterialService::count();
        $wageServicesCount = WageService::count();

        return view('service.index_tab_wage_services')
            ->with(compact('wageServices'))
            ->with(compact('materialServicesCount'))
            ->with(compact('wageServicesCount'));
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
        return view('wage_service.show')->with(compact('wageService'));
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
