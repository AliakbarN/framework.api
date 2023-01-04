<?php

namespace api\Facades\Middleware;

use Api\Facades\Interfaces\IHandleable;
use Api\Facades\Request;

class MiddlewareController
{
    protected array $middlewares = [];
    protected Request $request;
    protected MiddlewaresState $middlewaresState;
    protected array $middlewareAlias;

    public function __construct(
        array $middlewares,
        array $middlewareAlias,
        MiddlewaresState $middlewaresState,
        Request $request
    ) {
        $this->middlewares = $middlewares;
        $this->middlewaresState = $middlewaresState;
        $this->request = $request;
        $this->middlewareAlias = $middlewareAlias;
    }

    public function process(): void
    {
        $data = null;
        foreach ($this->middlewareAlias as $alias) {
            $middleware = new $this->middlewares[$alias];
            if ($middleware instanceof IHandleable) {
                $middleware->handle($this->request, $this->middlewaresState, $data);

                if (get_class($middleware) !== $this->middlewaresState->getCurrentMiddleware()) {
                    throw new \Exception('The method $next does not realize in middleware ' . get_class($middleware));
                }

                if (!$this->middlewaresState->isCurrentDataEmpty()) {
                    $data = $this->middlewaresState->getCurrentData();
                    if ($data instanceof \Throwable) {
                        throw $data;
                    }
                }
            } else {
                throw new \Exception(
                    'This middleware does not implement the IHandleable interface : ' . get_class($middleware)
                );
            }
        }
    }
}