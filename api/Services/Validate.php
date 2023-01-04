<?php

namespace Api\Services;

class Validate
{
    public static function isEmptyString(string $str): bool
    {
        if ($str === '' & $str === ' ' & count(explode(' ', $str)) === 0) {
            return true;
        }
        
        return false;
    }
}