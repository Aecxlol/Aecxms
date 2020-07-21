<?php

namespace App\Model;

use App\Service\DI;
use Exception as ExceptionAlias;
use PDO;

abstract class AbstractModel
{
    /**
     * @var PDO
     */
    private ?PDO $db = null;

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
     * @return PDO
     */
    private function dbConnect(string $host, string $dbName, string $user, string $password): PDO
    {
        try {
            $this->db = new PDO('mysql:host=' . $host . ';dbname=' . $dbName . ';charset=utf8', $user, $password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->db;
        } catch (ExceptionAlias $e) {
            die(sprintf('Could not log in to the Database : %s', $e->getMessage()));
        }
    }

    /**
     * @param string $selection
     * @param string $table
     * @return array
     */
    protected function select(string $selection, string $table): array
    {
        $req = $this->db->prepare('SELECT ' . $selection . ' FROM ' . $table);
        $req->execute();
        $req->setFetchMode(PDO::FETCH_ASSOC);
        return $req->fetchAll();
    }
}