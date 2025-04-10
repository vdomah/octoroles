<?php namespace Vdomah\Roles\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateVdomahRolesConditionPermission extends Migration
{
    public function up()
    {
        Schema::create('vdomah_roles_condition_permission', function($table)
        {
            $table->integer('condition_id')->unsigned();
            $table->integer('permission_id')->unsigned();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('vdomah_roles_condition_permission');
    }
}