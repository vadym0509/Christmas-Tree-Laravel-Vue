<?php

namespace App\Exceptions;

use Exception;

class CartException extends Exception
{
    public function __construct($message, $originalException)
    {
        parent::__construct($message, 500);
    }
}
