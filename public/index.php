<?php

use App\Service\DI;

//spl_autoload_register(function ($classCalledNamespace) {
//    $privateFolderPath = str_replace('public', 'private', __DIR__);
//    $className         = strtolower(str_replace('App', '', $classCalledNamespace));
//
//    var_dump($classCalledNamespace);
//    if (count(explode('\\', $className)) > 1) {
//        include $privateFolderPath . $className . '.php';
//    } else {
//        for ($i = 0; $i < count(scandir('../private/')); $i++) {
//            if (isset(array_flip(scandir('../private/' . scandir('../private')[$i]))[ucfirst($className) . '.php'])) {
//                include $privateFolderPath . '\\' . scandir('../private/')[$i] . '\\' . ucfirst($className) . '.php';
//                break;
//            }
//        }
//    }
//});

//spl_autoload_register(static function ($class) {
//
//    $filename = preg_replace(['/^App\\\\/', '/\\\\/'], [realpath(sprintf('%s/private/', dirname(__DIR__, 1))) . '/', '/'], $class) . '.php';
//    var_dump($filename);
//    if (file_exists($filename)) {
//        return require $filename;
//    }
//    throw new Exception(sprintf('Class %s not found.', $class));
//});

spl_autoload_register(function ($class) {
   var_dump($class);
});
require ('../private/service/DI.php');

DI::getInstance()->get('App\Service\router');
