<?php

namespace Api\Routing;

use api\Services\Dto\RouteDto;
use Api\Services\Helper;


final class Route
{
    static string $uriPrefix = 'api';
    protected static array $routes = [];
    protected static array $middlewares = [];

    public static function setUriPrefix(string $prefix): void
    {
        self::$uriPrefix = $prefix;
    }

    protected static function addRoute(RouteDto $route): void
    {
        if (isset($route->method) & isset($route->uri) & isset($route->action)) {
            if (Helper::hasRoute(self::$routes, $route)['isHas']) {
                throw new \Exception('The route' . $route['uri'] . 'has already been added');
            }

            self::$routes[] = $route;
        } else {
            throw new \BadFunctionCallException('The action or uri or method in the route is uncorrected or not given');
        }
    }

    public static function getAllUris(): array
    {
        $routes = self::getAllRoutes();
        $uris = [];

        foreach ($routes as $route) {
            if (isset($route->uri)) {
                $uris[] = $route->uri;
            }
        }

        return $uris;
    }

    public static function getRouteByPath(string $path) :RouteDto
    {
        foreach (self::$routes as $route) {
            if ($route['uri'] === $path) return $route;
        }

        return new RouteDto('', '');
    }

    protected static function addMiddle(string $middleAlias): bool
    {
        if (in_array($middleAlias, self::$middlewares)) {
            return false;
        }

        self::$middlewares[] = $middleAlias;
        return true;
    }

    public static function getAllRoutes(): array
    {
        return self::$routes;
    }

    public static function getAllMiddlewares(): array
    {
        return self::$middlewares;
    }

    public static function getRoute(RouteDto $options): RouteDto
    {
        $params = Helper::hasRoute(self::$routes, $options);

        if (!$params['isHas']) {
            return new RouteDto('', '');
        }
        return self::$routes[$params['index']];
    }

    public static function get(string $uri, string|array $action = null): self
    {
        self::addRoute(
            new RouteDto(
                $uri,
                'get',
                $action
            )
        );
        return new self();
    }

    public static function post(string $uri, string|array $action = null): self
    {
        self::addRoute(
            new RouteDto(
                $uri,
                'post',
                $action
            )
        );
        return new self();
    }

    public static function delete(string $uri, string|array $action = null): self
    {
        self::addRoute(
            new RouteDto(
                $uri,
                'delete',
                $action
            )
        );
        return new self();
    }

    public static function update(string $uri, string|array $action = null): self
    {
        self::addRoute(
            new RouteDto(
                $uri,
                'update',
                $action
            )
        );
        return new self();
    }

    public static function middle(string $middleAlias): self
    {
        if (!self::addMiddle($middleAlias)) {
            throw new \Exception('The middleware has already been added');
        }
        return new self();
    }
}