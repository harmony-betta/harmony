<?php
/*
PsySh configure file (see http://psysh.org/#configure)
Usage
    Global
        Copy this file to ~\.config\psysh\config.php, where ~ is your home dir
        for example (Windows): C:\Users\MY_USER\.config\psysh\config.php
    Local (per-project):
        0. Navigate to project ROOT and type in CMD:
            composer require psy/psysh
        1. Copy this file to ROOT/bin/psysh.config.php
        2. Create in the same folder file ROOT/bin/psysh.php with this content:
            <?php
            require_once __DIR__ . '/../vendor/autoload.php';
            // insert code to bootstrap your app or do some things you need here
            // this should be at the very bottom of the file
            $configFile = __DIR__.'/psysh.config.php';
            $sh = new \Psy\Shell(new \Psy\Configuration(['configFile' => $configFile]));
            $sh->setScopeVariables(get_defined_vars());
            $sh->run();
        Example: http://www.qopy.me/wffnzVfHTByLZykSeg7t2Q
*/
/* copy of class Illuminate\Foundation\Console\IlluminateCaster */
class Caster {
    const PREFIX_VIRTUAL = "\0~\0";
    const PREFIX_PROTECTED = "\0*\0";
    public static function castCollection($collection)
    {
        return [
            self::PREFIX_VIRTUAL.'all' => $collection->all(),
        ];
    }
    public static function castModel($model)
    {
        $attributes = array_merge(
            $model->getAttributes(), $model->getRelations()
        );
        $visible = array_flip(
            $model->getVisible() ?: array_diff(array_keys($attributes), $model->getHidden())
        );
        $results = [];
        foreach (array_intersect_key($attributes, $visible) as $key => $value) {
            $results[(isset($visible[$key]) ? self::PREFIX_VIRTUAL : self::PREFIX_PROTECTED).$key] = $value;
        }
        return $results;
    }
    public static function castBuilder($object) {
        return [self::PREFIX_VIRTUAL.'toSql()'=>$object->toSql()];
    }
}
return [
    'casters' => [
        // Eloquent
        'Illuminate\Support\Collection' => 'Caster::castCollection',
        'Illuminate\Database\Eloquent\Model' => 'Caster::castModel',
        'Illuminate\Database\Eloquent\Builder' => 'Caster::castBuilder',
        // Doctrine
        // result dump will look like http://www.qopy.me/D6R2fYEQSw6wjV7NDaPMxw
        'AppBundle\Entity\Base' => function ($obj) {
            $reflect = new \ReflectionClass($obj);
            $props = $reflect->getProperties();
            $result = [];
            foreach ($props as $prop) {
                $name = $prop->name;
                if (in_array($name, ['__cloner__', '__initializer__'])) continue;
                $prop->setAccessible(true);
                $result[$prop->name] = $prop->getValue($obj);
            }
            return $result;
        },
        // Yii
        'yii\db\ActiveRecord' => function($obj) {
            return $obj->getAttributes();
        }
    ],
    'prompt' => '>>> ',
    'requireSemicolons' => true,
];