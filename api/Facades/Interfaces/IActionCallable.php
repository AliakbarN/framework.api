<?php

namespace api\Facades\Interfaces;

use api\Facades\Response;

interface IActionCallable
{
    public function callAction(string $method, array $parameters): Response;
}