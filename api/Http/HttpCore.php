<?php

namespace Api\Http;

use api\Facades\Middleware\MiddlewareController;
use api\Facades\Middleware\MiddlewaresState;
use Api\Facades\Request;
use Api\Routing\Route;
use Api\Services\Config;

class HttpCore
{
    protected array $request;
    protected array $routes;
    protected Config $conf;
    protected array $middlewares;

    public function __construct()
    {
        $request = new Request();
        $this->request = [
            'uri' => $request->getRequestUri(),
            'method' => $request->getRequestMethod()
        ];
        $this->conf = new Config();
        $this->routes = Route::getAllRoutes();
        $this->registerMiddlewares();
    }

    protected function registerMiddlewares(): void
    {
        $this->middlewares = $this->conf->getMiddlewares();
    }

    public function handle(): void
    {
        $middleContr = new MiddlewareController(
            $this->conf->getMiddlewares(),
            Route::getAllMiddlewares(),
            new MiddlewaresState(),
            new Request()
        );
        $middleContr->process();
    }
}