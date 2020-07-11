<?php


namespace App\Model;


use App\Service\DI;

abstract class AbstractModel
{
    public function __construct()
    {
//        DI::getInstance()->get('Config');
    }

    protected function dbConnect(string $host, string $dbName, string $user, string $password) {
        try {
            $db = new \PDO('mysql:host='.$host.';dbname='.$dbName.';charset=utf8', $user, $password);
        }
        catch (\Exception $e) {
            die("An error has occured : ".$e->getMessage());
        }
    }

    protected function select(){

    }
}