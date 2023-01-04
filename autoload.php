<?php

spl_autoload_register(function ($className) {
    $className[0] = 'a';
    require __DIR__ . '/' . $className . '.php';
});