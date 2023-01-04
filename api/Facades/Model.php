<?php

namespace T4\Orm;

use Api\Api;
use Api\Facades\Interfaces\IActiveRecord;
use T4\Dbal\Connection;

abstract class Model
    implements IActiveRecord
{

    const PK = '__id';

    public function getPk()
    {
        return $this->{static::PK};
    }

    const HAS_ONE = 'hasOne';
    const BELONGS_TO = 'belongsTo';
    const HAS_MANY = 'hasMany';
    const MANY_TO_MANY = 'manyToMany';

    protected static $schema = [];

    protected static $extensions = [];

    private static $connections;


    public static function getSchema() :array
    {
        static $schema = null;
        if (null === $schema) {
            $class = get_called_class();
            $schema = $class::$schema;
            $extensions = $class::getExtensions();
            foreach ($extensions as $extension) {
                $extensionClassName = '\\T4\\Orm\\Extensions\\' . ucfirst($extension);
                if (class_exists($extensionClassName)) {
                    $extension = new $extensionClassName;
                    $schema['columns'] = $extension->prepareColumns($schema['columns'], $class);
                    $schema['relations'] = $extension->prepareRelations(isset($schema['relations']) ? $schema['relations'] : [], $class);
                }
            }
        }
        return $schema;
    }

    public static function getColumns() :array
    {
        return static::getSchema()['columns'];
    }

    public static function getPivots($class, $relationName) :array
    {
        $schema = static::getSchema();
        if (empty($schema['pivots']) || empty($schema['pivots'][$class]) || empty($schema['pivots'][$class][$relationName])) {
            return [];
        } else {
            return $schema['pivots'][$class][$relationName];
        }
    }

    public static function getTableName() :string
    {
        $schema = static::getSchema();
        if (isset($schema['table']))
            return $schema['table'];
        else {
            $className = explode('\\', get_called_class());
            return strtolower(array_pop($className)) . 's';
        }
    }


    public static function getDbConnectionName() :string
    {
        $schema = static::getSchema();
        return !empty($schema['db']) ? $schema['db'] : 'default';
    }

    public static function setConnection($connection) :mixed
    {
        if (is_string($connection)) {
            $app = new Api();
            $connection = $app->db->{$connection};
        }
        self::$connections[get_called_class()] = $connection;
    }

    public static function getDbConnection() :mixed
    {
        if ( !isset(self::$connections[get_called_class()]) ) {
            static::setConnection(static::getDbConnectionName());
        }
        return self::$connections[get_called_class()];
    }

    public static function getDbDriver() :mixed
    {
        return static::getDbConnection()->getDriver();
    }

    public static function getExtensions() :mixed
    {
        return !empty(static::$extensions) ?
            array_merge(['standard', 'relations'], static::$extensions) :
            ['standard', 'relations'];
    }

}