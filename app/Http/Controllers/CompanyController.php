<?php

namespace App\Http\Controllers;

use App\Address;
use App\Company;
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
            ->withCount(['people', 'projects'])
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
        $addresses = Address::all();

        return view('company.create')
            ->with('company', null)
            ->with('currentAddress', null)
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

        if ($request->filled('address_id')) {
            $company->address()->sync(Address::find($request->address_id));
        } elseif ($request->filled('street_number') && $request->filled('postcode') && $request->filled('city')) {
            $addressData = Arr::only($validatedData, ['street_number', 'postcode', 'city']);

            $address = Address::where($addressData)->first();

            if ($address) {
                $company->address()->sync($address);

                return redirect()->route('companies.index')
                    ->with('info', 'Die Firma wurde erfolgreich angelegt und mit der bereits vorhandenen Adresse erknüpft.');
            } else {
                $company->address()->sync(Address::create($validatedData));
            }
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
                return view('company.show_tab_overview')->with(compact('company'));
            case 'projects':
                $company->load('projects')->whereHas('projects', function ($query) use ($input) {
                    $query->order($input);
                })->paginate(15)->appends($request->except('page'));

                return view('company.show_tab_projects')->with(compact('company'));
            case 'people':
                $company->load('people')->whereHas('people', function ($query) use ($input) {
                    $query->order($input);
                })->paginate(15)->appends($request->except('page'));

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
        $addresses = Address::all();

        return view('company.edit')
            ->with(compact('company'))
            ->with('currentAddress', $currentAddress)
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

        if ($request->filled('address_id')) {
            $company->address()->sync(Address::find($request->address_id));
        } elseif ($request->filled('street_number') && $request->filled('postcode') && $request->filled('city')) {
            $addressData = Arr::only($validatedData, ['street_number', 'postcode', 'city']);

            $address = Address::where($addressData)->first();

            if ($address) {
                $company->address()->sync($address);

                return redirect()->route('companies.index')
                    ->with('info', 'Die Firma wurde erfolgreich bearbeitet. Die bereits vorhandene Adresse wurde zugewiesen.');
            } else {
                $company->address()->sync(Address::create($validatedData));
            }
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

        $company->delete();

        if ($address && $address->companies_count + $address->people_count == 1) {
            $address->delete();

            return redirect()->route('companies.index')
                ->with('success', 'Die Firma und verknüpfte Adresse wurden erfolgreich entfernt.');
        }

        return redirect()->route('companies.index')->with('success', 'Die Firma wurde erfolgreich entfernt.');
    }
}
