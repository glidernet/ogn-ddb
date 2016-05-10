<?php
include 'config.php';

class Database
{
    private static $db = $config['db'];
    private static $cont = null;

    public function __construct()
    {
        die('Init function is not allowed');
    }

    public static function connect()
    {
        // Only one connection allowed through whole application
       if (null == self::$cont) {
           try {
               self::$cont = new PDO('mysql:host=' . self::$db['host'] . ';dbname=' . self::$db['dbname'], self::$db['user'], self::$db['pass']);
               self::$cont->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           } catch (PDOException $e) {
               echo 'Connection failed: '.$e->getMessage();
           }
       }

        return self::$cont;
    }

    public static function disconnect()
    {
        self::$cont = null;
    }
}
