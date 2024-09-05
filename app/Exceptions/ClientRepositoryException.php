<?php

namespace App\Exceptions;

use Exception;

class ClientRepositoryException extends Exception
{
    public function __construct($message = "Client non trouvé.")
    {
        parent::__construct($message);
    }

    public function render($request)
    {
        return response()->json([
            'message' => $this->getMessage()
        ], 404);
    }
}
