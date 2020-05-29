<?php namespace Vdomah\Roles\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateVdomahRolesPermissions extends Migration
{
    public function up()
    {
        if (Schema::hasTable('vdomah_roles_permissions'))
            return;

        Schema::create('vdomah_roles_permissions', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->text('name');
            $table->string('code', 255);
            $table->integer('role_id')->unsigned();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('vdomah_roles_permissions');
    }
}
