<?php


namespace App\Model;


use App\Service\DI;

class AbstractModel
{
    public function __construct()
    {
        $dbCfg = DI::getInstance()->get('App\Service\Config');
        $this->dbConnect($dbCfg->getHost(), $dbCfg->getDbName(), $dbCfg->getUser(), $dbCfg->getPassword());
    }

    protected function dbConnect(string $host, string $dbName, string $user, string $password) {
        try {
            new \PDO('mysql:host='.$host.';dbname='.$dbName.';charset=utf8', $user, $password);
        }
        catch (\Exception $e) {
            die(sprintf('Couldn\'t log in to the Database : %s', $e->getMessage()));
        }
    }

    protected function select(){

    }
}