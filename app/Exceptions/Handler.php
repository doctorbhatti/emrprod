<?php

namespace App\Exceptions;

use App\Lib\Logger;
use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler {
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Throwable $e) {
        // Log the error message and stack trace using the custom Logger
        Logger::error($e->getMessage(), $e->getTrace());
        
        // Call the parent report method to handle the default reporting
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $e) {
        // Check the type of exception and return the appropriate response
        switch ($e) {
            case($e instanceof \App\Exceptions\NotFoundException):
                // Render a custom 404 error page
                return response(view('errors.404'), 404);
                break;

            case($e instanceof AuthorizationException):
                // Render a custom 401 error page
                return response(view('errors.401'), 401);
                break;

            case($e instanceof ValidationException):
                // Use the default rendering for validation exceptions
                return parent::render($request, $e);
                break;

            default:
                // Render a generic 500 error page for all other exceptions
                return response(view('errors.error'), 500);
                break;
        }
    }
}
