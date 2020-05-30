<?php namespace Vdomah\Roles\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuddiesAddRoleIdColumn extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('lovata_buddies_users') ||
            Schema::hasColumn('lovata_buddies_users', 'role_id')
        )
            return;

        Schema::table('lovata_buddies_users', function($table)
        {
            $table->integer('role_id')->nullable();
        });
    }

    public function down()
    {
        if (!Schema::hasTable('lovata_buddies_users') ||
            !Schema::hasColumn('lovata_buddies_users', 'role_id')
        )
            return;

        Schema::table('lovata_buddies_users', function($table)
        {
            $table->dropColumn('role_id');
        });
    }
}