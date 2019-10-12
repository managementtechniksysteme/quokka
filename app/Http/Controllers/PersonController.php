<?php

namespace App\Http\Controllers;

use App\Address;
use App\Company;
use App\Http\Requests\PersonStoreRequest;
use App\Http\Requests\PersonUpdateRequest;
use App\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $people = Person::order($request->input())
            ->with('address')
            ->paginate(15)
            ->appends($request->except('page'));

        return view('person.index')->with(compact('people'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $addresses = Address::order()->get();

        $currentCompany = null;

        if ($request->filled('company')) {
            $currentCompany = Company::find($request->company);
        }

        $companies = Company::order()->get();

        return view('person.create')
            ->with('person', null)
            ->with('currentAddress', null)
            ->with('addresses', $addresses->toJson())
            ->with('currentCompany', $currentCompany)
            ->with('companies', $companies->toJson());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PersonStoreRequest $request)
    {
        $validatedData = $request->validated();

        $person = Person::create($validatedData);

        if ($request->filled('address_id')) {
            $person->address()->sync(Address::find($request->address_id));
        } elseif ($request->filled('street_number') && $request->filled('postcode') && $request->filled('city')) {
            $addressData = Arr::only($validatedData, ['street_number', 'postcode', 'city']);

            $address = Address::where($addressData)->first();

            if ($address) {
                $person->address()->sync($address);

                return redirect()->route('people.index')
                    ->with('info', 'Die Person wurde erfolgreich angelegt und mit der bereits vorhandenen Adresse verknüpft.');
            } else {
                $person->address()->sync(Address::create($validatedData));
            }
        }

        return redirect()->route('people.index')->with('success', 'Die Person wurde erfolgreich angelegt.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function show(Person $person)
    {
        $person->load('address')->load('company');

        return view('person.show')->with(compact('person'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function edit(Person $person)
    {
        $currentAddress = optional($person->address)->first() ?? null;
        $addresses = Address::order()->get();

        $currentCompany = $person->company;
        $companies = Company::order()->get();

        return view('person.edit')
            ->with(compact('person'))
            ->with('currentAddress', $currentAddress)
            ->with('addresses', $addresses->toJson())
            ->with('currentCompany', $currentCompany)
            ->with('companies', $companies->toJson());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function update(PersonUpdateRequest $request, Person $person)
    {
        $validatedData = $request->validated();

        $person->update($validatedData);

        if ($request->filled('address_id')) {
            $person->address()->sync(Address::find($request->address_id));
        } elseif ($request->filled('street_number') && $request->filled('postcode') && $request->filled('city')) {
            $addressData = Arr::only($validatedData, ['street_number', 'postcode', 'city']);

            $address = Address::where($addressData)->first();

            if ($address) {
                $person->address()->sync($address);

                return redirect()->route('people.index')
                    ->with('info', 'Die Person wurde erfolgreich bearbeitet. Die bereits vorhandene Adresse wurde zugewiesen.');
            } else {
                $person->address()->sync(Address::create($validatedData));
            }
        }

        return redirect()->route('people.index')->with('success', 'Die Person wurde erfolgreich bearbeitet.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function destroy(Person $person)
    {
        $address = $person->address()->withCount('companies')->withCount('people')->first();

        $person->delete();

        if ($address && $address->companies_count + $address->people_count == 1) {
            $address->delete();

            return redirect()->route('people.index')
                ->with('success', 'Die Person und verknüpfte Adresse wurden erfolgreich entfernt.');
        }

        return redirect()->route('people.index')->with('success', 'Die Person wurde erfolgreich entfernt.');
    }
}
