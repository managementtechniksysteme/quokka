<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    private const LOGFOLDER = 'exceptions';

    /**
     * Define the execption context.
     *
     * @return array
     */
    protected function context()
    {
        return array_merge(parent::context(), [
            'uuid' => Str::uuid(),
        ]);
    }

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);

        if(config('app.env') === 'production' && !$this->isHttpException($exception)) {
            $folder = Handler::LOGFOLDER;
            $exceptionLog = "{$folder}/{$this->context()['uuid']}.log";
            Storage::put($exceptionLog, $exception->getTraceAsString());
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if(config('app.env') === 'production' && !$this->isHttpException($exception)) {
            return response()
                ->view('errors.500', [
                    'exception' => $exception,
                    'exceptionUuid' => $this->context()['uuid']
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return parent::render($request, $exception);
    }
}
