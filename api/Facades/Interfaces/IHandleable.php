<?php

namespace Api\Facades\Interfaces;

use Api\Facades\Request;

interface IHandleable
{
    public function handle(Request $request, callable $next, $data): void;
}