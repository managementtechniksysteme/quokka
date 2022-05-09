<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonStoreRequest;
use App\Http\Requests\PersonUpdateRequest;
use App\Models\Address;
use App\Models\Company;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class PersonController extends Controller
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
        $this->authorizeResource(Person::class, 'person');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $people = Person::filterSearch($request->input())
            ->order($request->input())
            ->with('address')
            ->paginate(Auth::user()->settings->list_pagination_size)
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

        $pivotData = [['address_type' => 'private']];

        if ($request->filled('address_id')) {
            $person->address()->sync(array_combine([$request->address_id], $pivotData));
        } elseif ($request->filled('address_name') && $request->filled('street_number')
            && $request->filled('postcode') && $request->filled('city')) {
            $addressData = Arr::only($validatedData, ['street_number', 'postcode', 'city']);
            $addressData['name'] = $validatedData['address_name'];

            $address = Address::where($addressData)->first() ?? Address::create($addressData);

            $person->address()->sync(array_combine([$address->id], $pivotData));
        }

        return redirect()->route('people.show', $person)->with('success', 'Die Person wurde erfolgreich angelegt.');
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
        $currentCompany = $person->company;

        $validatedData = $request->validated();

        $person->update($validatedData);

        $pivotData = [['address_type' => 'private']];

        if ($request->filled('address_id')) {
            $person->address()->sync(array_combine([$request->address_id], $pivotData));
        } elseif ($request->filled('address_name') && $request->filled('street_number')
            && $request->filled('postcode') && $request->filled('city')) {
            $addressData = Arr::only($validatedData, ['street_number', 'postcode', 'city']);
            $addressData['name'] = $validatedData['address_name'];

            $address = Address::where($addressData)->first() ?? Address::create($addressData);

            $person->address()->sync(array_combine([$address->id], $pivotData));
        } else {
            $person->address()->detach();
        }

        if(isset($validatedData['company_id'])) {
            $company = Company::find($validatedData['company_id']);
            $person->company()->associate($company);
            $person->save();
        } else {
            $person->company()->disassociate();
            $person->save();
        }

        if($currentCompany !== null && $person->company_id !== $currentCompany->id) {
            $currentCompany->contactPerson()->disassociate();
            $currentCompany->save();
        }

        return redirect()->route('people.show', $person)->with('success', 'Die Person wurde erfolgreich bearbeitet.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Person $person)
    {
        $redirect = $this->getConditionalRedirect($request->redirect, $person);

        $address = $person->address()->withCount('companies')->withCount('people')->first();

        $person->address()->detach();
        $person->delete();

        if ($address && $address->companies_count + $address->people_count == 1) {
            $address->delete();
        }

        return $redirect->with('success', 'Die Person wurde erfolgreich entfernt.');
    }

    private function getConditionalRedirect($target, $person)
    {
        switch ($target) {
            case 'company':
                $route = 'companies.show';
                $parameters = ['company' => $person->company, 'tab' => 'people'];
                break;
            case 'show':
                $route = 'people.show';
                $parameters = ['person' => $person];
                break;
            default:
                $route = 'people.index';
                $parameters = [];
                break;
        }

        return redirect()->route($route, $parameters);
    }
}
