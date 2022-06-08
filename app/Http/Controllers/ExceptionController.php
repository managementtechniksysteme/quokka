<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ExceptionController extends Controller
{
    private const LOGFOLDER = 'exceptions';

    public function index(Request $request)
    {
        $this->authorize('tools-viewexceptions');

        $files = File::files(Storage::path(self::LOGFOLDER));

        $exceptions = collect(
            array_map(function ($element) {
                return [
                    'uuid' => $element->getBasename('.log'),
                    'created_at' => (new Carbon($element->getMTime()))->setTimezone(config('app.timezone')),
                ];
            }, $files)
        );

        if($request->has('search')) {
            $exceptions = $exceptions->filter(function ($exception) use ($request) {
                return str_contains($exception['name'], $request->search);
            });
        }

        $exceptions = $exceptions->sortByDesc('created_at')->values()->paginate(Auth::user()->settings->list_pagination_size)
            ->appends($request->except('page'));

        return view('exception.index')->with(compact('exceptions'));
    }

    function show($uuid)
    {
        $this->authorize('tools-viewexceptions');

        $filepath = Storage::path(self::LOGFOLDER.DIRECTORY_SEPARATOR.$uuid.'.log');

        if (! File::exists($filepath)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $exception = [
            'uuid' => $uuid,
            'content' => File::get($filepath),
            'created_at' => (new Carbon(File::lastModified($filepath)))->setTimezone(config('app.timezone'))
        ];

        return view('exception.show')->with(compact('exception'));
    }

    function destroy($uuid)
    {
        $this->authorize('tools-deleteexceptions');

        $filepath = Storage::path(self::LOGFOLDER.DIRECTORY_SEPARATOR.$uuid.'.log');

        if (! File::exists($filepath)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        File::delete($filepath);

        return redirect()->route('exceptions.index')->with('success', 'Die Fehlerdatei wurde erfolgreich entfernt.');
    }
}
