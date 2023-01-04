<?php

namespace api\Facades;

use api\Facades\Interfaces\IActionCallable;

abstract class Controller implements IActionCallable
{
    public function callAction(string $method, array $parameters): Response
    {
        if (isset($this->{$method})) {
            return $this->{$method}(...array_values($parameters));
        }

        throw new \Exception('The method ' . $method . ' does not exist');
    }
}