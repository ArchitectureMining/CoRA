<?php

namespace Cozp\Utils;

use Exception;

class DatabaseUtils
{
    public static function connect($settings)
    {
        try {
            $db = new \PDO($settings['dsn'], $settings['user'], $settings['pass']);

            // standard attribute settings
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

            return $db;
        } catch (Exception $e) {
            // die('Connection to database failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
