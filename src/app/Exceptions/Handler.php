<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $e
     *
     * @return JsonResponse|\Illuminate\Http\Response|Response
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        // dd($request->all(), get_class($e), $e->getMessage(), $e->getTraceAsString());
        if ($request->ajax()) {
            if ($e instanceof AuthorizationException) {
                return response()
                    ->json([
                        'success' => false,
                        'message' => Response::$statusTexts[Response::HTTP_UNAUTHORIZED],
                        'data' => null
                    ], Response::HTTP_UNAUTHORIZED);
            } else if ($e instanceof BadRequestException) {
                return response()
                    ->json([
                        'success' => false,
                        'message' => $e->getMessage(),
                        'data' => null
                    ], Response::HTTP_BAD_REQUEST);
            } else if ($e instanceof ModelNotFoundException) {
                return response()
                    ->json([
                        'success' => false,
                        'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                        'data' => null
                    ], Response::HTTP_NOT_FOUND);
            } else if ($e instanceof ValidationException) {
                return response()
                    ->json([
                        'success' => false,
                        'message' => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                        'data' => $e->errors()
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                return response()
                    ->json([
                        'success' => false,
                        'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                        'data' => null
                    ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return parent::render($request, $e);
    }
}
