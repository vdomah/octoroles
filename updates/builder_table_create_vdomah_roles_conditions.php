<?php namespace Vdomah\Roles\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateVdomahRolesConditions extends Migration
{
    public function up()
    {
        Schema::create('vdomah_roles_conditions', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('code');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('vdomah_roles_conditions');
    }
}