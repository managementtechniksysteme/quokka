<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountingIndexRequest;
use App\Http\Requests\AccountingStoreRequest;
use App\Http\Requests\AccountingUpdateRequest;
use App\Http\Resources\AccountingCollection;
use App\Http\Resources\AccountingResource;
use App\Models\Accounting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AccountingController extends Controller
{
    protected function resourceAbilityMap()
    {
        return array_merge(parent::resourceAbilityMap(), [
            'showEmail' => 'email',
            'email' => 'email',
        ]);
    }

    public function resourceMethodsWithoutModels()
    {
        return array_merge(parent::resourceMethodsWithoutModels(), ['download']);
    }

    public function __construct()
    {
        $this->authorizeResource(Accounting::class, 'accounting');
    }

    public function index(Request $request)
    {
        $currentAccounting = Accounting::filterPermissions()
            ->filterSearch($request->validate((new AccountingIndexRequest($request->query()))->rules()))
            ->order()
            ->with('employee.person')
            ->with('project')
            ->with('service')
            ->paginate(Auth::user()->settings->list_pagination_size);

        return new AccountingCollection($currentAccounting);
    }

    public function store(AccountingStoreRequest $request)
    {
        $accounting = Accounting::make($request->validated());
        $accounting->employee()->associate(Auth::user()->employee);
        $accounting->save();

        return response()->json($accounting, Response::HTTP_CREATED);
    }

    public function show(Accounting $accounting)
    {

        return new AccountingResource($accounting);
    }

    public function update(AccountingUpdateRequest $request, Accounting $accounting)
    {
        $accounting->update($request->validated());

        return new AccountingResource(Accounting);
    }

    public function destroy(Accounting $accounting)
    {
        $accounting->delete();

        return new JsonResponse(['status' => 'ok'], Response::HTTP_OK);
    }

}
