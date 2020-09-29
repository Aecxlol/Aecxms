<?php

namespace Aecxms\Service;

use Aecxms\Exception\CmsException;
use Aecxms\Helper\Helper;

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
        $object     = Helper::getObjectNamespace($object);
        $objectName = strtolower(trim($object));

        if (!isset($this->registry[$objectName])) {
            try {
                $this->registry[$objectName] = new $object();
            } catch (CmsException $e) {
                die(sprintf('There is no class named %s, please make sure that the name and namespace are valid. You can also check if you declared the DI with the correct namespace.', $object));
            }
        }
        return $this->registry[$objectName];
    }
}