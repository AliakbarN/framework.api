<?php

namespace Api\Services;

use api\Services\Dto\RouteDto;

class Helper
{
    public static function hasRoute(array $routes, RouteDto $options): array
    {
        $result = ['index' => -1, 'isHas' => false];

        if (!isset($options['method']) & !isset($options['uri'])) {
            throw new \InvalidArgumentException();
        }

        for ($i = 0; $i < count($routes); $i++) {
            if ($options['method'] === $routes[$i]['method'] & $options['uri'] === $routes[$i]['uri']) {
                $result['index'] = $i;
                $result['isHas'] = true;
                break;
            }
        }

        return $result;
    }
}