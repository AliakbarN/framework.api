<?php

namespace api\Facades;

use Api\Routing\Route;
use api\Services\Dto\RouteDto;
use Api\Services\Uri;
use Api\Services\Validate;
use Api\Facades\Request;
use api\Facades\Response;

final class RouteManager
{
    protected array $uris;
    protected array $routes;
    protected string $requestUri;
    protected Uri $uriprocessor;

    public function __construct(string $requestUri)
    {
        $this->uris = Route::getAllUris();
        $this->requestUri = $requestUri;
        $this->uriprocessor = new Uri($this->uris);
    }

    public function registerRoutes(): void
    {
        $this->routes = Route::getAllRoutes();
    }

    protected function getNeededRoute(): RouteDto
    {
        $matchedRoutePath = $this->uriprocessor->getMatchedRoutePath();

        if (!Validate::isEmptyString($matchedRoutePath)) {
            $routeCurrent = Route::getRouteByPath($matchedRoutePath);
            return $routeCurrent;
        }

        return new RouteDto('', '');
    }

    public function processRequest() :void
    {
        $route = $this->getNeededRoute();
        $routeAction = $route['action'];

        if (gettype($routeAction) === 'array') {
            foreach($routeAction as $controllerClass => $action)
            {
                $controller = new $controllerClass();
                $controller->{$action}(new Request(), new Response());
            }
        } else if (gettype($routeAction) === 'string') {
            $contrArr = explode('@', $routeAction);
            $controllerClass = $contrArr[0];
            $action = $contrArr[1];

            $controller = new $controllerClass();

            $controller->{$action}(new Request(), new Response());
        }
    }
}