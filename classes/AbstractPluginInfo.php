<?php namespace Vdomah\Roles\Classes;


abstract class AbstractPluginInfo
{
    private static $_instance = null;

    private function __construct() {}

    public static function instance()
    {
        if (self::$_instance == null) {
            $classChild = get_called_class();
            self::$_instance = new $classChild;
        }

        return self::$_instance;
    }

    abstract function getPluginName();

    abstract function getUserClass();

    abstract function getUserControllerClass();

    abstract function getBackendMenuName();

    abstract function authUser();
}