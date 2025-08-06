<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Foundation\Exceptions\Handler as BaseHandler;

/**
 * Custom exception handler for API responses.
 *
 * Catches common exceptions and returns JSON-formatted responses
 * with appropriate HTTP status codes.
 */
class ApiExceptionHandler extends BaseHandler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $e)
    {
        // Handle API or JSON-expected requests
        if ($request->is('api/*') || $request->expectsJson()) {
            // 401 – Not authenticated
            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Unauthenticated',
                    'message' => $e->getMessage() ?: 'Authentication required.',
                ], Response::HTTP_UNAUTHORIZED);
            }

            // 403 – Authenticated but not authorized
            if ($e instanceof AuthorizationException) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Forbidden',
                    'message' => $e->getMessage() ?: 'You do not have permission.',
                ], Response::HTTP_FORBIDDEN);
            }

            if ($e instanceof ModelNotFoundException) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Not Found',
                    'message' => $e->getMessage() ?: 'Resource not found.',
                ], Response::HTTP_NOT_FOUND);
            }

            // 400 – Validation errors
            if ($e instanceof ValidationException) {
                return response()->json([
                    'success'  => false,
                    'error'    => 'Validation Failed',
                    'messages' => $e->errors(),
                ], Response::HTTP_BAD_REQUEST);
            }

            // Other HTTP exceptions (e.g., abort(400), abort(403))
            if ($e instanceof HttpExceptionInterface) {
                $status = $e->getStatusCode();
                return response()->json([
                    'success' => false,
                    'error'   => $status,
                    'message' => $e->getMessage() ?: Response::$statusTexts[$status] ?? 'Error',
                ], $status);
            }

            // Fallback for all other exceptions
            $this->report($e);
            return response()->json([
                'success' => false,
                'error'   => 'Server Error',
                'message' => 'Something went wrong.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Non-API or HTML requests fallback
        return parent::render($request, $e);
    }
}
