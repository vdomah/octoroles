<?php namespace Vdomah\Roles\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateVdomahRolesPermissionRole extends Migration
{
    public function up()
    {
        Schema::create('vdomah_roles_permission_role', function($table)
        {
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('vdomah_roles_permission_role');
    }
}
