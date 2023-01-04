<?php

namespace Api\Services;

class Config
{
    protected array $conf;
    public string $driver;
    public string $dbname;
    public string $host;
    public string $port;
    public string $user;
    public string $password;
    
    public function __construct()
    {
        $this->conf = require __DIR__ . '/../../config/api.php';
    }

    public function getPrefix () :string
    {
        return $this->conf['url']['prefix'];
    }

    public function getMiddlewares() :array
    {
        return $this->conf['http']['middleware'];
    }
}