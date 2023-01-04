<?php

namespace api\Services\Dto;

final class RouteDto
{
    public string $uri;
    public string $method;
    public mixed $action;

    public function __construct(string $uri, string $method, mixed $action = null)
    {
        $this->uri = $uri;
        $this->method = $method;
        $this->action = $action;
    }
}