<?php

namespace App\Support\Filesystem;

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;
use Slim\Http\UploadedFile;

class Storage
{
    public static $fs;
    public static $adapter;

    public static function fs()
    {
        self::$adapter = new Local(dirname(dirname(dirname(__DIR__))).'/storage/');
        self::$fs = new Filesystem(self::$adapter);
        return self::$fs;
    }
    
    public static function listAllFiles($dir=null, $bool=false)
    {
        if($dir === null && $bool === false){
            return self::fs()->listContents();
        }else{
            return self::fs()->listContents($dir, $bool);
        }
    }

    public static function createDirectory($dirname)
    {
       return self::fs()->createDir($dirname);
    }

    public static function deleteDir($dirname)
    {
       return self::fs()->deleteDir($dirname);
    }

    public static function write($path, $content)
    {
       return self::fs()->write($path, $content);
    }

    public static function update($path, $content)
    {
       return self::fs()->update($path, $content);
    }

    public static function put($path, $content)
    {
       return self::fs()->put($path, $content);
    }
    
    public static function readFile($pathToFile)
    {
        return self::fs()->read($pathToFile);
    }

    public static function hasFile($pathToFile)
    {
        return self::fs()->has($pathToFile);
    }

    public static function deleteFile($pathToFile)
    {
        return self::fs()->delete($pathToFile);
    }

    public static function readAndDelete($pathToFile)
    {
        return self::fs()->readAndDelete($pathToFile);
    }

    public static function rename($old, $new)
    {
        return self::fs()->rename($old, $new);
    }

    public static function copy($old, $new)
    {
        return self::fs()->copy($old, $new);
    }

    public static function getMimeType($pathToFile)
    {
        $mimetype = self::fs()->getMimetype($pathToFile);
        return $mimetype;
    }

    public static function getTimestamp($pathToFile)
    {
        $timestamp = self::fs()->getTimestamp($pathToFile);
        return $timestamp;
    }

    public static function getSize($pathToFile)
    {
        $size = self::fs()->getSize($pathToFile);
        return $size;
    }

    public static function store($directory, UploadedFile $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);
    
        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
    
        return $filename;
    }

    public static function storeAs($directory, $name, UploadedFile $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $filename = $name.'.'.$extension;
    
        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
    
        return $filename;
    }
    
}