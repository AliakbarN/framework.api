<?php

namespace Api;

require_once __DIR__ . '/../public/route.php';

use Api\Http\HttpCore;
use Api\Routing\Route;
use Api\Services\Config;

class Api
{

    public mixed $db;

    public function __construct()
    {
        $conf = new Config();
        $httpCore = new HttpCore();
        Route::setUriPrefix($conf->getPrefix());
        $httpCore->handle();
    }

}