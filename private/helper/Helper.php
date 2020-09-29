<?php

namespace Aecxms\Helper;

class Helper
{
    public static function removeNamespace($class)
    {
        $lastIndex = explode('\\', $class);
        return end($lastIndex);
    }

    /**
     * Get the object's namespace by browsing its file
     * @param $object
     * @return string
     */
    public static function getObjectNamespace($object): string
    {
        $namespace         = "";
        $privateFolderPath = dirname(__DIR__);
        $subFoldersCount   = count(scandir($privateFolderPath));
        // Check in every folders
        for ($i = 0; $i < $subFoldersCount; $i++) {
            // If there is a file with the same name as the $object provided
            if (file_exists($file = $privateFolderPath . DIRECTORY_SEPARATOR . scandir($privateFolderPath)[$i] . DIRECTORY_SEPARATOR . $object . '.php')) {
                // Convert the file into and array
                $handle = file($file);
                // Browse the array
                for ($j = 0; $j < count($handle); $j++) {
                    // Till we find the word namespace
                    if (strpos($handle[$j], "namespace") !== false) {
                        $namespace = str_replace("namespace ", "", trim($handle[$j]));
                        $namespace = str_replace(";", "", $namespace);
                        $namespace .= DIRECTORY_SEPARATOR . $object;
                        break;
                    }
                }
            }
        }
        return $namespace;
    }
}