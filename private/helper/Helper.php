<?php

namespace Aecxms\Helper;

class Helper
{
    public static function removeNamespace($class)
    {
        $lastIndex = explode('\\', $class);
        return end($lastIndex);
    }
}