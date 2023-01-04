<?php

namespace api\Facades\Middleware;

class MiddlewaresState
{
    protected static string $currentMiddleware;
    protected static mixed $data;
    protected static array $implementedMiddleware = [];

    public function __invoke(string $className, mixed $data = null): void
    {
        static::$data = $data;
        static::$currentMiddleware = $className;
        static::$implementedMiddleware[] = $className;
    }

    public function isCurrentDataEmpty(): bool
    {
        if (static::$data === null) {
            return true;
        }
        return false;
    }

    public function getCurrentData(): mixed
    {
        return static::$data;
    }

    public function getCurrentMiddleware(): string
    {
        if (self::$currentMiddleware) {
            return static::$currentMiddleware;
        }

        return '';
    }
}