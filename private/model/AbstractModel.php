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
        $key_first                = null;
        $key_last                 = null;
        $whereClauseKeysAndValues = [];
        $sql                      = 'SELECT ' . $selection . ' FROM ' . $table;

        /**
         * @todo
         * récupérer champs de la db et comparer avec ce qui a été rentré dans le tableau WHERE
         * pour voir si la clé rentrée existe dans la base
         * si elle existe pas, message d'erreur
         */

        if ($options) {
            // If the WHERE array exists
            if (array_key_exists('WHERE', $options)) {
                $key_first = array_key_first($options['WHERE']);
                array_push($whereClauseKeysAndValues, $key_first, $options['WHERE'][$key_first]);

                // If the WHERE array has only one field
                if (count($options['WHERE']) === self::ONE_WHERE_CONDITION) {
                    $sql = 'SELECT ' . $selection . ' FROM ' . $table . ' WHERE ' . $key_first . '=:' . $key_first;

                    // If the WHERE array has two fields
                } elseif (count($options['WHERE']) === self::TWO_WHERE_CONDITION) {
                    $key_last = array_key_last($options['WHERE']);
                    array_push($whereClauseKeysAndValues, $key_last, $options['WHERE'][$key_last]);
                    $sql = 'SELECT ' . $selection . ' FROM ' . $table . ' WHERE ' . $key_first . '=:' . $key_first . ' AND ' . $key_last . '=:' . $key_last;

                    // Cannot have more than two fields
                } else {
                    die('You cannot have empty or more than two parameters in the WHERE clause.');
                }
            }
        }
        $req = $this->db->prepare($sql);

        if ($options) {
            if (array_key_exists('WHERE', $options)) {
                for ($i = 0; $i < count($options['WHERE']); $i++) {
                    // $i*2 is all the odd values and ($i*2)+1 is all the even values
                    // so the first loop will get the value of the the key 0 and 1
                    // the second one will get the key 2 and 3 etc
                    $req->bindParam($whereClauseKeysAndValues[$i * 2], $whereClauseKeysAndValues[($i * 2) + 1], PDO::PARAM_STR);
                }
            }
        }

        $req->execute();
        $req->setFetchMode(PDO::FETCH_ASSOC);

        if ($req->rowCount() === self::NO_RESULTS_FOUND) {
            die('SELECT ERROR : No results found. Something might be wrong in the options you gave to the SELECT request.');
        } else {
            return $req->fetchAll();
        }
    }
}