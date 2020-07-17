<?php

namespace App\Model;

use App\Service\DI;

abstract class AbstractModel
{
    /**
     * @var object|null
     */
    private ?object $db = null;

    /**
     * AbstractModel constructor.
     */
    public function __construct()
    {
        $dbCfg = DI::getInstance()->get('App\Service\Config');
        $this->dbConnect($dbCfg->getHost(), $dbCfg->getDbName(), $dbCfg->getUser(), $dbCfg->getPassword());
    }

    /**
     * @param string $host
     * @param string $dbName
     * @param string $user
     * @param string $password
     * @return \PDO
     */
    private function dbConnect(string $host, string $dbName, string $user, string $password): object
    {
        try {
            return $this->db = new \PDO('mysql:host=' . $host . ';dbname=' . $dbName . ';charset=utf8', $user, $password);
        } catch (\Exception $e) {
            die(sprintf('Couldn\'t log in to the Database : %s', $e->getMessage()));
        }
    }

    /**
     * @param string $selection
     * @param string $table
     * @return mixed
     */
    protected function select(string $selection, string $table)
    {
        return $this->db->prepare('SELECT ' . $selection . ' FROM ' . $table);
    }
}