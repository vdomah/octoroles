<?php namespace Vdomah\Roles\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateLovataBuddiesUsersRenameRoleId extends Migration
{
    public $table = 'lovata_buddies_users';

    public $column = 'vdomah_roles_role_id';

    public $column_old = 'role_id';

    public function up()
    {
        if (!Schema::hasTable($this->table) ||
            Schema::hasColumn($this->table, $this->column) ||
            !Schema::hasColumn($this->table, $this->column_old)
        )
            return;

        Schema::table($this->table, function($table)
        {
            $table->renameColumn($this->column_old, $this->column);
        });
    }

    public function down()
    {
        if (!Schema::hasTable($this->table) ||
            Schema::hasColumn($this->table, $this->column_old) ||
            !Schema::hasColumn($this->table, $this->column)
        )
            return;

        Schema::table($this->table, function($table)
        {
            $table->renameColumn($this->column, $this->column_old);
        });
    }
}