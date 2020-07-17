<?php

use App\Service\DI;

spl_autoload_register(function ($class) {
    $privateFolderPath = str_replace('public', 'private', __DIR__);
    $className = strtolower(str_replace('App', '', $class)).'.php';
    // faire regex pour ucfirst le nom de la classe
    $fileName = $privateFolderPath.$className;
    if(!file_exists($fileName)){
        throw new Exception(sprintf('Class %s not found', $class));
    }
    require $fileName;
});

DI::getInstance()->get('App\Service\Router');