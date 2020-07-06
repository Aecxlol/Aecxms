<?php


namespace App\Model;


abstract class Model
{
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