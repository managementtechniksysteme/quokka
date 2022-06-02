<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyStoreRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Models\Address;
use App\Models\ApplicationSettings;
use App\Models\Company;
use App\Models\Person;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
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
        $this->authorizeResource(Company::class, 'company');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $companies = Company::filterSearch($request->search)
            ->order($request->sort)
            ->with('address')
            ->with('operatorAddress')
            ->withCount('people')
            ->withCount('projects')
            ->paginate(Auth::user()->settings->list_pagination_size)
            ->appends($request->except('page'));

        return view('company.index')->with(compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $addresses = Address::order()->get();
        $people = Person::whereNull('company_id')->order()->get();

        return view('company.create')
            ->with('company', null)
            ->with('currentAddress', null)
            ->with('currentOperatorAddress', null)
            ->with('addresses', $addresses->toJson())
            ->with('currentContactPerson', null)
            ->with('people', $people->toJson());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CompanyStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyStoreRequest $request)
    {
        $validatedData = $request->validated();

        $company = Company::create($validatedData);

        $pivotData = [['address_type' => 'company']];

        if ($request->filled('address_id')) {
            $company->address()->sync(array_combine([$request->address_id], $pivotData));
        } elseif ($request->filled('address_name') && $request->filled('street_number')
            && $request->filled('postcode') && $request->filled('city')) {
            $addressData = Arr::only($validatedData, ['street_number', 'postcode', 'city']);
            $addressData['name'] = $validatedData['address_name'];

            $address = Address::where($addressData)->first() ?? Address::create($addressData);

            $company->address()->sync(array_combine([$address->id], $pivotData));
        }

        $pivotData = [['address_type' => 'operator']];

        if ($request->filled('operator_address_id')) {
            $company->operatorAddress()->sync(array_combine([$request->operator_address_id], $pivotData));
        } elseif ($request->filled('operator_address_name') && $request->filled('operator_street_number')
            && $request->filled('operator_postcode') && $request->filled('operator_city')) {
            $addressData = [];

            $addressData['name'] = $validatedData['operator_address_name'];
            $addressData['street_number'] = $validatedData['operator_street_number'];
            $addressData['postcode'] = $validatedData['operator_postcode'];
            $addressData['city'] = $validatedData['operator_city'];

            $address = Address::where($addressData)->first() ?? Address::create($addressData);

            $company->operatorAddress()->sync(array_combine([$address->id], $pivotData));
        }

        if (isset($validatedData['contact_person_id'])) {
            $contactPerson = Person::find($validatedData['contact_person_id'])->load('company');

            if ($contactPerson !== null && $contactPerson->company !== null) {
                return redirect()
                    ->route('companies.show', $company)
                    ->with('warning', 'Die Firma wurde erfolgreich angelegt. Die Person ist bereits einer anderen Firma zugeordnet.');
            }

            elseif ($contactPerson->company === null) {
                $contactPerson->company()->associate($company);
                $contactPerson->save();
                $company->contactPerson()->associate($contactPerson);
                $company->save();
            }
        }

        return redirect()->route('companies.show', $company)->with('success', 'Die Firma wurde erfolgreich angelegt.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company, Request $request)
    {
        $company->loadCount('people')->loadCount('projects');

        switch ($request->tab) {
            case 'overview':
                $company->load('address')->load('contactPerson');

                return view('company.show_tab_overview')->with(compact('company'));

            case 'projects':
                if(Auth::user()->cannot('viewAny', Project::class)) {
                    return redirect()->route('company.show', [$company, 'tab' => 'overview']);
                }

                $projects = Project::where('company_id', $company->id)
                    ->filterSearch($request->search)
                    ->order($request->sort)
                    ->withCount('tasks')
                    ->withCount('memos')
                    ->withCount('serviceReports')
                    ->paginate(Auth::user()->settings->list_pagination_size)
                    ->appends($request->except('page'));

                $projectOverwallCostsWarningPercentage = ApplicationSettings::get()->project_overall_costs_warning_percentage;
                $projectMaterialCostsWarningPercentage = ApplicationSettings::get()->project_material_costs_warning_percentage;
                $projectWageCostsWarningPercentage = ApplicationSettings::get()->project_wage_costs_warning_percentage;

                return view('company.show_tab_projects')
                    ->with(compact('company'))
                    ->with(compact('projects'))
                    ->with(compact('projectOverwallCostsWarningPercentage'))
                    ->with(compact('projectMaterialCostsWarningPercentage'))
                    ->with(compact('projectWageCostsWarningPercentage'));

            case 'people':
                if(Auth::user()->cannot('viewAny', Person::class)) {
                    return redirect()->route('company.show', [$company, 'tab' => 'overview']);
                }

                $people = Person::where('company_id', $company->id)
                    ->filterSearch($request->search)
                    ->order($request->sort)
                    ->paginate(Auth::user()->settings->list_pagination_size)
                    ->appends($request->except('page'));

                return view('company.show_tab_people')->with(compact('company'))->with(compact('people'));
            default:
                return redirect()->route('companies.show', [$company, 'tab' => 'overview']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        $currentAddress = optional($company->address)->first() ?? null;
        $currentOperatorAddress = optional($company->operatorAddress)->first() ?? null;
        $addresses = Address::order()->get();

        $currentContactPerson = $company->contactPerson;
        $people = Person::where('company_id', $company->id)->orWhereNull('company_id')->order()->get();

        return view('company.edit')
            ->with(compact('company'))
            ->with('currentAddress', $currentAddress)
            ->with('currentOperatorAddress', $currentOperatorAddress)
            ->with('addresses', $addresses->toJson())
            ->with('currentContactPerson', $currentContactPerson)
            ->with('people', $people->toJson());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CompanyUpdateRequest  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyUpdateRequest $request, Company $company)
    {
        $validatedData = $request->validated();

        $company->update($validatedData);

        $pivotData = [['address_type' => 'company']];

        if ($request->filled('address_id')) {
            $company->address()->sync(array_combine([$request->address_id], $pivotData));
        } elseif ($request->filled('address_name') && $request->filled('street_number')
            && $request->filled('postcode') && $request->filled('city')) {
            $addressData = Arr::only($validatedData, ['street_number', 'postcode', 'city']);
            $addressData['name'] = $validatedData['address_name'];

            $address = Address::where($addressData)->first() ?? Address::create($addressData);

            $company->address()->sync(array_combine([$address->id], $pivotData));
        } else {
            $company->address()->detach();
        }

        $pivotData = [['address_type' => 'operator']];

        if ($request->filled('operator_address_id')) {
            $company->operatorAddress()->sync(array_combine([$request->operator_address_id], $pivotData));
        } elseif ($request->filled('operator_address_name') && $request->filled('operator_street_number')
            && $request->filled('operator_postcode') && $request->filled('operator_city')) {
            $addressData = [];

            $addressData['name'] = $validatedData['operator_address_name'];
            $addressData['street_number'] = $validatedData['operator_street_number'];
            $addressData['postcode'] = $validatedData['operator_postcode'];
            $addressData['city'] = $validatedData['operator_city'];

            $address = Address::where($addressData)->first() ?? Address::create($addressData);

            $company->operatorAddress()->sync(array_combine([$address->id], $pivotData));
        } else {
            $company->operatorAddress()->detach();
        }

        if (isset($validatedData['contact_person_id'])) {
            $contactPerson = Person::find($validatedData['contact_person_id'])->load('company');

            if ($contactPerson->company !== null &&
                $contactPerson->company_id !== $company->id) {
                return redirect()
                    ->route('companies.show', $company)
                    ->with('warning', 'Die Firma wurde erfolgreich bearbeitet. Die Person ist bereits einer anderen Firma zugeordnet.');
            } else {
                if($contactPerson->company == null) {
                    $contactPerson->company()->associate($company);
                    $contactPerson->save();
                }
                $company->contactPerson()->associate($contactPerson);
                $company->save();
            }
        } else {
            $company->contactPerson()->disassociate();
            $company->save();
        }

        return redirect()->route('companies.show', $company)->with('success', 'Die Firma wurde erfolgreich bearbeitet.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $address = $company->address()->withCount('companies')->withCount('people')->first();
        $operatorAddress = $company->operatorAddress()->withCount('companies')->withCount('people')->first();

        $company->address()->detach();
        $company->operatorAddress()->detach();
        $company->delete();

        if ($address && $address->companies_count + $address->people_count == 1) {
            $address->delete();
        }

        if ($operatorAddress && $operatorAddress->companies_count + $operatorAddress->people_count == 1) {
            $operatorAddress->delete();
        }

        return redirect()->route('companies.index')->with('success', 'Die Firma wurde erfolgreich entfernt.');
    }
}
