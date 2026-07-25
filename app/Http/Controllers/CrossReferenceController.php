<?php

namespace App\Http\Controllers;

use App\Support\GlobalSearch\CrossReferenceResolver;
use App\Support\GlobalSearch\GlobalSearch;
use Illuminate\Http\Request;

class CrossReferenceController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:search');

        // A short, broad query (as typed live while autocompleting) can make
        // GlobalSearch::searchFuzzy fetch a very large uncapped result set
        // across every searchable model before Fuse narrows it down; the
        // debugbar's own profiling of that (queries, bindings, timeline) then
        // pushes memory over the limit on top of it. Both actions here are
        // called on every keystroke and return plain JSON that no toolbar
        // ever attaches to, so there's nothing to profile in the first place.
        app('debugbar')->disable();
    }

    public function search(Request $request)
    {
        $query = trim((string) $request->query('query', ''));

        if ($query === '') {
            return response()->json([]);
        }

        return GlobalSearch::searchFuzzy($query)
            ->take(8)
            ->values()
            ->map(fn ($result) => [
                'token' => CrossReferenceResolver::tokenFor($result->getModel(), $result->getId()),
                'type' => $result->getType(),
                'name' => $result->getName(),
                'route' => $result->getRoute(),
                'icon' => view('partials.model_icon', ['model' => $result->getModel()])->render(),
            ]);
    }

    public function resolve(Request $request)
    {
        $tokens = array_unique((array) $request->input('tokens', []));

        $results = [];

        foreach ($tokens as $token) {
            $result = CrossReferenceResolver::resolve((string) $token);

            if ($result) {
                $results[$token] = [
                    'token' => $token,
                    'type' => $result->getType(),
                    'name' => $result->getName(),
                    'route' => $result->getRoute(),
                    'icon' => view('partials.model_icon', ['model' => $result->getModel()])->render(),
                ];
            }
        }

        return response()->json($results);
    }
}
