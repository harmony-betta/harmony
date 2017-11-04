<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Schedules extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('schedules', function($table) {
            $table->increments('id');
            $table->timestamps();
        });

    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->drop('schedules');
    }
}