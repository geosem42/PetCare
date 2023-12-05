<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof QueryException) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'A database error occurred.', 'error' => $e->getMessage()], 500);
            }

            if ($request->inertia()) {
                return redirect()->back()->with('error', 'A database error occurred.');
            }
        }
        
        if ($e instanceof ModelNotFoundException) {
            if ($request->expectsJson()) {

                return response()->json(['message' => 'Resource not found.', 'error' => $e->getMessage()], 404);
            }

            if ($request->inertia()) {
                return redirect()->back()->with('error', 'Resource not found.');
            }
        }

        if ($e instanceof ValidationException) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Validation failed.',
                    'errors' => $e->validator->errors()
                ], 422);
            }

            if ($request->inertia()) {
                return redirect()->back()->withErrors($e->errors());
            }
        }

        if ($e instanceof NotFoundHttpException) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Resource not found.', 'error' => $e->getMessage()], 404);
            }
    
            if ($request->inertia()) {
                return redirect()->back()->with('error', 'Resource not found.');
            }
        }
    
        if ($e instanceof MethodNotAllowedHttpException) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Method not allowed.', 'error' => $e->getMessage()], 405);
            }
    
            if ($request->inertia()) {
                return redirect()->back()->with('error', 'Method not allowed.');
            }
        }

        if ($e instanceof BadMethodCallException) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Method not found.', 'error' => $e->getMessage()], 500);
            }
    
            if ($request->inertia()) {
                return redirect()->back()->with('error', 'Method not found.');
            }
        }

        return parent::render($request, $e);
    }
}
