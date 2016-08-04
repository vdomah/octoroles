<?php namespace Vdomah\Roles\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration104 extends Migration
{
    public function up()
    {
        Schema::table('users', function($table)
        {
            $table->integer('role_id');
        });
    }

    public function down()
    {
        Schema::table('users', function($table)
        {
            $table->dropColumn('role_id');
        });
    }
}