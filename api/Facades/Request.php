<?php

namespace Api\Facades;

class Request
{
    protected array $request;

    public function __construct()
    {
        $this->request = $_SERVER;
    }

    public function getRequestMethod(): string
    {
        return $this->request['REQUEST_METHOD'];
    }

    public function getRequestUri(): string
    {
        return $this->request['REQUEST_URI'];
    }
}