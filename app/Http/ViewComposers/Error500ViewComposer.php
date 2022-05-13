<?php

namespace App\Http\ViewComposers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class Error500ViewComposer
{
    private const LOGFOLDER = 'exceptions';

    public function compose(View $view)
    {
        $uuid = Str::uuid();

        if(config('app.env') === 'production') {
            $folder = Error500ViewComposer::LOGFOLDER;
            $exceptionLog = "{$folder}/{$uuid}.log";

            $postParameters = Request::post();

            $postParametersString = rtrim(array_reduce(array_keys($postParameters), function ($string, $key) use ($postParameters) {
               return $string . $key . ' => ' . $postParameters[$key] . ', ';
            }), ', ');

            Storage::put($exceptionLog,
                'Timestamp: ' . Carbon::now()->toDateTimeString() . PHP_EOL .
                'User: ' . (Auth::id() ?? 'guest') . PHP_EOL .
                'Request: ' . Request::method() . ' ' . Request::fullUrl() . PHP_EOL .
                'Parameters: ' . $postParametersString . PHP_EOL .
                'Exception:' . PHP_EOL .
                $view->exception->getMessage() . PHP_EOL .
                'in ' . $view->exception->getFile() . '(' . $view->exception->getLine() .')' . PHP_EOL .
                $view->exception->getTraceAsString()
            );
        }

        $view->with('exceptionUuid', $uuid);
    }
}
