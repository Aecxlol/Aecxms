<?php

use Aecxms\Exception\CmsException;

class Autoloader
{
    public static function register() {
        spl_autoload_register(function ($class) {
            $privateFolderPath = str_replace('public', 'private', dirname(__DIR__));
            $className = strtolower(str_replace('Aecxms', '', $class)).'.php';
            /**
             * @todo
             * faire regex pour ucfirst le nom de la classe
             */
            $fileName = $privateFolderPath.$className;
            if(!file_exists($fileName)){
                throw new CmsException(sprintf('Class %s not found', $class));
            }
            require $fileName;
        });
    }
}