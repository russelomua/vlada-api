<?php
namespace Vlada\Database;

use Vlada\MySQL;

/**
 * @property MySQL $database
 */
class Database {
    public function __get($name)
    {
        global $database;
        if ($name == 'database')
            return $database;
    }
}