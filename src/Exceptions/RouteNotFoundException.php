<?php

namespace EasyRoute\Exceptions;

use Exception;

class RouteNotFoundException extends Exception
{
    public function __construct(string $message = 'Route not found!')
    {
        parent::__construct($message);
    }
}