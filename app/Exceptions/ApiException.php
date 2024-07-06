<?php

namespace App\Exceptions;

class ApiException extends \Exception
{
    public function render()
    {
        response()->json(['message' => $this->getMessage()], $this->getCode() ?? 500);
    }
}
