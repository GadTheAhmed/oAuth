<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        //\Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
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
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->json(['scode'=>405,'message'=>'some Errors happened','data'=>[],'errors'=>['Method Not Allowed, Expected : '.$e->getHeaders()['Allow']]],405);
        }
        if ($e instanceof NotFoundHttpException) {
            return response()->json(['scode'=>404,'message'=>'some Errors happened','data'=>[],'errors'=>['Requested Endpoint Not Found']],404);
        }
        if ($e instanceof \Illuminate\Validation\ValidationException) {
            return response()->json(['scode'=>400,'message'=>'some Error happened','data'=>[],'errors'=>$e->validator->getMessageBag()],400);
        }
        if ($e instanceof ModelNotFoundException) {
            return response()->json(['scode'=>404,'message'=>'some Error happened','data'=>[],'errors'=>['User Not Found']],404);
        }
        return response()->json(['scode'=>400,'message'=>'some Error happened','data'=>[],'errors'=>[$e->getMessage()]],400);
        return parent::render($request, $e);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
