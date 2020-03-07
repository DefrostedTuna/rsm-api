<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;

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

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        if ($request->route() && in_array('api', $request->route()->middleware())) {
            $request->headers->set('Accept', 'application/json');
        }

        // dd(get_class($exception));

        if ($request->expectsJson()) {
            return new JsonResponse([
                'success' => false,
                'message' => $exception->getMessage(),
            ], $this->getHttpStatusCode($exception));
        }

        return parent::render($request, $exception);
    }

    /**
     * Parses the status code associated with the exception.
     *
     * @param  \Exception  $e
     *
     * @return int
     */
    protected function getHttpStatusCode(Exception $e): int
    {
        if (method_exists($e, 'getStatusCode')) {
            $code = $e->getStatusCode();
        } else if ($e->status && $e->status >= 100) {
            $code = $e->status;
        } else {
            $code = 500;
        }

        return $code;
    }
}
