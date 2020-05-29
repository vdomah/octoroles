<?php namespace Vdomah\Roles\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateVdomahRolesRoles extends Migration
{
    public function up()
    {
        if (Schema::hasTable('vdomah_roles_roles'))
            return;

        Schema::create('vdomah_roles_roles', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 255);
            $table->integer('parent_id')->unsigned()->nullable();
            $table->string('code', 255);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('vdomah_roles_roles');
    }
}
