<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LogbookIndexRequest;
use App\Http\Requests\LogbookStoreRequest;
use App\Http\Requests\LogbookUpdateRequest;
use App\Http\Resources\LogbookCollection;
use App\Http\Resources\LogbookResource;
use App\Http\Resources\SelectOptionCollection;
use App\Models\Logbook;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogbookController extends Controller
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
        $this->authorizeResource(Logbook::class, 'logbook');
    }

    public function index(Request $request)
    {
        $currentLogbook = Logbook::filterPermissions()
            ->filterSearch($request->validate((new LogbookIndexRequest($request->query()))->rules()))
            ->order()
            ->with('employee.person')
            ->with('project')
            ->with('vehicle')
            ->paginate(Auth::user()->settings->list_pagination_size);

        return new LogbookCollection($currentLogbook);
    }

    public function store(LogbookStoreRequest $request)
    {
        $logbook = Logbook::make($request->validated());
        $logbook->employee()->associate(Auth::user()->employee);
        $logbook->save();

        return new JsonResponse($logbook, Response::HTTP_CREATED);
    }

    public function show(Logbook $logbook)
    {

        return new LogbookResource($logbook);
    }

    public function update(LogbookUpdateRequest $request, Logbook $logbook)
    {
        $logbook->update($request->validated());

        return new LogbookResource($logbook);
    }

    public function destroy(Logbook $logbook)
    {
        $logbook->delete();

        return new JsonResponse(['status' => 'ok'], Response::HTTP_OK);
    }

    public function locationSelectOptions() {
        $origins = Logbook::distinct('origin')->select(['origin as text']);
        $locations = Logbook::distinct('destination')
            ->select(['destination as text'])
            ->union($origins)
            ->orderBy('text')
            ->get();

        foreach ($locations as $index => $location) {
            $location->id = $index;
        };

        return new SelectOptionCollection($locations);
    }
}
