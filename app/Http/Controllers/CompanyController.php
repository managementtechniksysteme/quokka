<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Company;
use App\Http\Requests\CompanyStoreRequest;
use App\Http\Requests\CompanyUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CompanyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $companies = Company::order($request->input())
            ->with('address')
            ->with('operatorAddress')
            ->withCount('people')
            ->withCount('projects')
            ->paginate(15)
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

        return view('company.create')
            ->with('company', null)
            ->with('currentAddress', null)
            ->with('currentOperatorAddress', null)
            ->with('addresses', $addresses->toJson());
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

        return redirect()->route('companies.index')->with('success', 'Die Firma wurde erfolgreich angelegt.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company, Request $request)
    {
        $input = $request->input();
        $company->loadCount('people')->loadCount('projects');

        switch ($request->tab) {
            case 'overview':
                $company->load('address');

                return view('company.show_tab_overview')->with(compact('company'));
            case 'projects':
                $company->load(['projects' => function ($query) use ($input) {
                    $query->order($input)->withCount('tasks')->withCount('memos');
                }])->paginate(15)->appends($request->except('page'));

                return view('company.show_tab_projects')->with(compact('company'));
            case 'people':
                $company->load(['people' => function ($query) use ($input) {
                    $query->order($input);
                }])->paginate(15)->appends($request->except('page'));

                return view('company.show_tab_people')->with(compact('company'));
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

        return view('company.edit')
            ->with(compact('company'))
            ->with('currentAddress', $currentAddress)
            ->with('currentOperatorAddress', $currentOperatorAddress)
            ->with('addresses', $addresses->toJson());
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

        return redirect()->route('companies.index')->with('success', 'Die Firma wurde erfolgreich bearbeitet.');
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
