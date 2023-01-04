<?php

namespace Api\Services;

use Api\Facades\Request;
use Api\Services\Validate;

class Uri
{
    protected string $uri;
    protected array $routeUris;

    public function __construct(array $uris)
    {
        $request = new Request();
        $this->uri = $request->getRequestUri();

        if (!Validate::isEmptyString($this->uri)) throw new \Exception('The current uri is incorrect');

        $this->routeUris = $uris;
    }

    protected function isHasAnyParams(string $routeUri): bool
    {
        if (!str_contains($routeUri, '/:')) {
            return false;
        }
        return true;
    }

    protected function isMatched(string $routeUri): bool
    {
        $routeUriParams = explode('/', $routeUri);
        $uriParams = explode('/', $this->uri);

        if (!Validate::isEmptyString($routeUri)) {
            if ($this->isHasAnyParams($routeUri)) {
                for ($i = 0; $i < count($routeUriParams); $i++) { 
                    if (str_contains($routeUriParams[$i], ':')) {
                        continue;
                    } else {
                        if ($routeUriParams[$i] === $uriParams[$i]) continue;
                        else return false;
                    }
                }
            } else {
                for ($i = 0; $i <  count($routeUriParams); $i++) { 
                    if ($routeUriParams[$i] !== $uriParams[$i]) return false;
                }
            }
        }

        return true;
    }

    public function getMatchedRoutePath() :string
    {
        foreach($this->routeUris as $routeUri)
        {
            if ($this->isMatched($routeUri)) return $routeUri;
        }

        return '';
    }

    public function getParams(string $routeUri): array
    {
        $result = [];
        $matchedRoutePath = $this->getMatchedRoutePath();

        if (Validate::isEmptyString($matchedRoutePath)) return $result;

        $matchedRoutePathParams = explode('/', $matchedRoutePath);
        $uriParams = explode('/', $this->uri);
        
        for ($i = 0; $i < count($matchedRoutePathParams); $i++) { 
            if (str_contains($matchedRoutePathParams[$i], ':')) {
                $result[] = $uriParams[$i];
            }
        }

        return $result;
    }
}