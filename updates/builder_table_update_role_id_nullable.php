<?php namespace Vdomah\Roles\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateRoleIdNullable extends Migration
{
    public $column = 'vdomah_role_id';

    public $tables;

    public function __construct()
    {
        $this->tables = ['users', 'lovata_buddies_users'];
    }

    public function up()
    {
        foreach ($this->tables as $table) {
            if (!Schema::hasTable($table))
                continue;

            if (!Schema::hasColumn($table, $this->column)) {
                Schema::table($table, function($table)
                {
                    $table->integer($this->column)->nullable();
                });
            } else {
                Schema::table($table, function($table)
                {
                    $table->integer($this->column)->nullable()->change();
                });
            }
        }
    }

    public function down()
    {

    }
}