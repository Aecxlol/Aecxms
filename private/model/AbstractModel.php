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
     * @var int
     */
    private const NO_RESULTS_FOUND = 0;

    /**
     * @var int
     */
    private const ONE_WHERE_CONDITION = 1;

    /**
     * @var int
     */
    private const TWO_WHERE_CONDITION = 2;

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
     * @param array|null $options
     * @return array
     */
    protected function select(string $selection, string $table, array $options = null): array
    {
        $key   = null;
        $value = null;
        $sql   = 'SELECT ' . $selection . ' FROM ' . $table;

        /**
         * @todo
         * récupérer champs de la db et comparer avec ce qui a été rentré dans le tableau WHERE
         * pour voir si la clé rentrée existe dans la base
         * si elle existe pas, message d'erreur
         */

        if ($options) {
            // If the WHERE array exists
            if (array_key_exists('WHERE', $options)) {
                // If the WHERE array has only one field
                if (count($options['WHERE']) === self::ONE_WHERE_CONDITION) {
                    // field's key
                    $key = array_key_first($options['WHERE']);
                    // key's value
                    $value = $options['WHERE'][array_key_first($options['WHERE'])];

                    $sql = 'SELECT ' . $selection . ' FROM ' . $table . ' WHERE ' . $key . '=:' . $key;
                    // If the WHERE array has two fields
                } elseif (count($options['WHERE']) === self::TWO_WHERE_CONDITION) {
                    var_dump("2 cond");
                    $sql = 'SELECT ' . $selection . ' FROM ' . $table . ' WHERE ' . implode("", array_keys($options['WHERE'])) . '=:' . implode("", array_keys($options['WHERE']));
                    // Cannot have more than two fields
                } else {
                    die('You cannot have more than two parameters in the WHERE condition.');
                }
            }
        }
        $req = $this->db->prepare($sql);
        if ($options) {
            $req->bindParam($key, $value, PDO::PARAM_STR);
        }
        $req->execute();
        $req->setFetchMode(PDO::FETCH_ASSOC);

        if ($req->rowCount() === self::NO_RESULTS_FOUND) {
            die('No results found for the SELECT request. Something might be wrong in your SQL statement.');
        } else {
            return $req->fetchAll();
        }
    }
}