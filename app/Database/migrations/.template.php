<?= "<?php";?>


use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class <?= ucfirst($className) ?> extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('<?= ((substr($className, -1) == 's')) ? strtolower(str_replace("CreateTable","", $className)) : strtolower(str_replace("CreateTable","", $className).'s') ?>', function($table) {
            $table->increments('id');
            $table->timestamps();
        });

    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->drop('<?= (((substr($className, -1) == 's')) ? strtolower(str_replace("CreateTable","", $className)) : strtolower(str_replace("CreateTable","", $className).'s') ) ?>');
    }
}