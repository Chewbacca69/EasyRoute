<?php

namespace EasyRoute\Exceptions;

use Exception;

class MethodNotAllowedException extends Exception
{
    public function __construct(string $message = 'Method not allowed for this route!')
    {
        parent::__construct($message);
    }
}