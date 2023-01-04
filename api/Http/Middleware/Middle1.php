<?php

namespace Api\Http\Middleware;

use Api\Facades\Interfaces\IHandleable;
use Api\Facades\Request;

class Middle1 implements IHandleable
{
    public function handle(Request $request, $next, $data): void
    {
        echo 'Middle1' . '<br />';
        $next(self::class, 'some data');
    }
}