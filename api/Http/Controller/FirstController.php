<?php

namespace api\Http\Controller;

use api\Facades\Controller as BaseController;
use Api\Facades\Request;
use api\Facades\Response;

final class FirstController extends BaseController
{
    public function someAction(Request $request, Response $response): mixed
    {
        return $response->send(['name' => 'Aliakbar']);
    }
}