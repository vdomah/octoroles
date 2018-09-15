<?php namespace Vdomah\Roles\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration104 extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('users') || Schema::hasColumn('users', 'role_id'))
            return;

        Schema::table('users', function($table)
        {
            $table->integer('role_id');
        });
    }

    public function down()
    {
        if (!Schema::hasTable('users') || !Schema::hasColumn('users', 'role_id'))
            return;

        Schema::table('users', function($table)
        {
            $table->dropColumn('role_id');
        });
    }
}