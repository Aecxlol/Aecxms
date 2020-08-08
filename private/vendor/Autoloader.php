<?php

use Aecxms\Exception\CmsException;

class Autoloader
{
    private const PROJECT_NAME = 'Aecxms';

    public static function register()
    {
        spl_autoload_register(function ($class) {
            $privateFolderPath = str_replace('public', 'private', dirname(__DIR__));
            $splittedNamespace = explode('\\', $class);
            $namespace         = strtolower($splittedNamespace[0] . '\\' . $splittedNamespace[1] . '\\');
            $className         = str_replace(strtolower(self::PROJECT_NAME), '', $namespace) . $splittedNamespace[2] . '.php';
            $fileName          = $privateFolderPath . $className;

            if (!file_exists($fileName)) {
                throw new CmsException(sprintf('Class %s not found', $class));
            }
            require $fileName;
        });
    }
}