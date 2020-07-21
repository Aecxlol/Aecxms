<?php

namespace App\Service;

use App\Exception\Exception;

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
         * @todo
         * faire en sorte que l'appel d'une classe ne nécessite pas de call le namespace associé
         */
        $objectName = strtolower(trim($object));

        if (!isset($this->registry[$objectName])) {
            try {
                $this->registry[$objectName] = new $object();
            } catch (Exception $e) {
                die(sprintf('There is no class named %s, please make sure that the name and namespace are valid.', $object));
            }
        }
        return $this->registry[$objectName];
    }
}