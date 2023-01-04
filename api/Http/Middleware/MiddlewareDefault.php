<?php

namespace Api\Http\Middleware;

use Api\Facades\Interfaces\IHandleable;
use Api\Facades\Request;

class MiddlewareDefault implements IHandleable
{
    public function handle(Request $request, $next, $data): void
    {
        echo 'MiddleDefault' . ' --- ' . $data . '<br />';
        $next(self::class);
    }
}