<?php namespace Vdomah\Roles\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateVdomahRolesPermissions extends Migration
{
    public function up()
    {
        Schema::table('vdomah_roles_permissions', function($table)
        {
            $table->integer('role_id')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('vdomah_roles_permissions', function($table)
        {
            $table->integer('role_id')->nullable(false)->change();
        });
    }
}
