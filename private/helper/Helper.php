<?php

namespace App\Helper;

class Helper
{
    public static function removeNamespace($class) {
        $lastIndex = explode('\\', $class);
        return end($lastIndex);
    }

    public static function getParentDirectory($file) {

    }
}