<?php

namespace Aecxms\Service;

use Aecxms\Exception\CmsException;

/**
 * Class DI
 * @package Aecxms\Service
 */
class DI
{
    /**
     * @var object|null
     */
    private static ?object $instance = null;

    /**
     * @var array
     */
    private array $registry = [];

    /**
     * DI constructor.
     */
    private function __construct()
    {
    }

    /**
     * @return DI|object|null
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new DI();
        }
        return self::$instance;
    }

    /**
     * @param $object
     * @return mixed
     */
    public function get($object)
    {
        /**
         * @todo doesnt go in the catch
         */
        $object = $this->getObjectNamespace($object);
        $objectName = strtolower(trim($object));

        if (!isset($this->registry[$objectName])) {
            try {
                $this->registry[$objectName] = new $object();
            } catch (CmsException $e) {
                die(sprintf('There is no class named %s, please make sure that the name and namespace are valid. You can also check if you declared the DI with the correct namespace.', $objectName));
            }
        }
        return $this->registry[$objectName];
    }

    /**
     * Get the object's namespace by browsing its file
     * @param $class
     * @return string
     */
    private function getObjectNamespace($class): string
    {
        /**
         * @todo if the file doesnt exist
         */
        $namespace         = "";
        $privateFolderPath = dirname(__DIR__);
        $subFoldersCount   = count(scandir($privateFolderPath));
        // Check in every folders
        for ($i = 0; $i < $subFoldersCount; $i++) {
            // If there is a file with the same name as the $object provided
            if (file_exists($file = $privateFolderPath . DIRECTORY_SEPARATOR . scandir($privateFolderPath)[$i] . DIRECTORY_SEPARATOR . $class . '.php')) {
                // Convert the file into and array
                $handle = file($file);
                // Browse the array
                for ($j = 0; $j < count($handle); $j++) {
                    // Till we find the word namespace
                    if (strpos($handle[$j], "namespace") !== false) {
                        $namespace = str_replace("namespace ", "", trim($handle[$j]));
                        $namespace = str_replace(";", "", $namespace);
                        $namespace .= DIRECTORY_SEPARATOR . $class;
                        break;
                    }
                }
            }
        }
        return $namespace;
    }
}