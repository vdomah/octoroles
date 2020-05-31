<?php namespace Vdomah\Roles\Classes;

use Schema;

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

    public function getUserRoleIdColumnName()
    {
        return 'vdomah_role_id';
    }

    public function checkRoleIdColumnExists()
    {
        $user_class = $this->getUserClass();
		$model = new $user_class;
        $table = $model->getTable();

		if (!Schema::hasTable($table) ||
            (Schema::hasTable($table) && Schema::hasColumn($table, $this->getUserRoleIdColumnName()))
        )
            return;

        Schema::table($table, function($table)
        {
            $table->integer($this->getUserRoleIdColumnName())->nullable();
        });
    }
}