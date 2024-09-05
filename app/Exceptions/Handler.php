<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
            return $this->handleException($e);
        });
    }
    protected function handleException(Throwable $e){
        if($e instanceof ModelNotFoundException){
            return response()->json([
                'status' => '404',
                'error' => 'Not Found',
                'success' =>false,
            ]);
        }

        return response()->json([
            'status' => '500',
            'message' => 'Erreur du serveur',
            'success' => false,
            'error' => $e->getMessage(),
        ],500);
    }
}
