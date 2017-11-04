<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Comments extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('comments', function($table) {
            $table->increments('id');
            $table->integer('comment_author_id');
            $table->integer('post_id');
            $table->timestamps();
        });

    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->drop('comments');
    }
}