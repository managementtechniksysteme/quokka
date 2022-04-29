<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountingIndexRequest;
use App\Http\Requests\AccountingStoreRequest;
use App\Http\Requests\AccountingUpdateRequest;
use App\Models\Accounting;
use App\Models\ApplicationSettings;
use App\Models\Person;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AccountingController extends Controller
{
    public function index(AccountingIndexRequest $request)
    {
        if($request->ajax()) {
            $currentAccounting = Accounting::filterSearch($request->validated())->order()->get();

            return response()->json($currentAccounting, Response::HTTP_OK);
        }

        $projects = Project::order()->get();
        $services = Service::order()->get();
        $employees = Person::has('employee')->order()->get();
        $currentEmployee = Auth::user()->employee->person;
        $servicesHourUnit = ApplicationSettings::get()->services_hour_unit ?? null;
        $minAccountingAmount = ApplicationSettings::get()->accounting_min_amount;
        $expandErrors = Auth::user()->settings->accounting_expand_errors;
        $filterDefaultDays = Auth::user()->settings->accounting_filter_default_days;
        $pageSize = Auth::user()->settings->list_pagination_size;

        return view('accounting.index')
            ->with('currentAccounting', null)
            ->with('projects', $projects->toJson())
            ->with('services', $services->toJson())
            ->with('employees', $employees->toJson())
            ->with('currentEmployee', $currentEmployee->toJson())
            ->with('servicesHourUnit', $servicesHourUnit)
            ->with('minAccountingAmount', $minAccountingAmount)
            ->with('expandErrors', json_encode($expandErrors))
            ->with('filterDefaultDays', json_encode($filterDefaultDays))
            ->with('pageSize', json_encode($pageSize));
    }


    public function store(AccountingStoreRequest $request)
    {
        $accounting = Accounting::make($request->validated());
        $accounting->employee()->associate(Auth::user()->employee);
        $accounting->save();

        return response()->json($accounting, Response::HTTP_CREATED);
    }

    public function update(AccountingUpdateRequest $request, Accounting $accounting)
    {
        $accounting->update($request->validated());

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function destroy(Accounting $accounting)
    {
        $accounting->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
