<?php

namespace T4\Dbal;

use Api\Services\Config;


class Connection
{

    protected $config;

    protected $pdo;

    public function __construct(Config $config)
    {
        $this->config = $config;
        try {
            $this->pdo = $this->getPdoObject($this->config);
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    protected function getPdoObject(Config $config)
    {
        $dsn = $config->driver . ':host=' . $config->host . ';dbname=' . $config->dbname;
        if (!empty($config->port)) {
            $dsn .= ';port=' . $config->port;
        }
        $options = [];

        $pdo = new \PDO($dsn, $config->user, $config->password, $options);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::ATTR_STATEMENT_CLASS, [Statement::class]);
        return $pdo;
    }

    public function getDriverName()
    {
        return (string)$this->config->driver;
    }


    public function quote(string $string, $parameter_type = \PDO::PARAM_STR)
    {
        return $this->pdo->quote($string, $parameter_type);
    }

    public function prepare(mixed $query)
    {
        $statement = $this->pdo->prepare($query);
        return $statement;
    }

    public function execute($query, array $params = [])
    {

        $params = array_merge($params, $query->getParams());

        $params = array_merge($params, $query->params);

        $statement = $this->pdo->prepare($query);
        return $statement->execute($params);
    }

    public function query($query, array $params = [])
    {

        $params = array_merge($params, $query->getParams());

        $params = array_merge($params, $query->params);


        $statement = $this->pdo->prepare($query);
        $statement->execute($params);
        return $statement;
    }

    public function lastInsertId($name = null)
    {
        return $this->pdo->lastInsertId($name);
    }

    public function getErrorInfo()
    {
        return $this->pdo->errorInfo();
    }

    public function __sleep()
    {
        return ['config'];
    }

    public function __wakeup()
    {
        $this->pdo = $this->getPdoObject($this->config);
    }

    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    public function rollbackTransaction()
    {
        return $this->pdo->rollBack();
    }

    public function commitTransaction()
    {
        return $this->pdo->commit();
    }

    public function inTransaction(): bool
    {
        return $this->pdo->inTransaction();
    }
}
