<?php

class MyPDO extends PDO {

    const DB_FLAG = '';
    const QUERY_FAILED = -1;

    public static array $cache = array();
    private static ?MyPDO $_instance = null;

    private function __construct() {
        $config = require __DIR__ . '/../config.php';
        $DB_HOST = $config['host'];
        $DB_PORT = $config['port'];
        $DB_NAME = $config['name'];
        $DB_USER = $config['user'];
        $DB_PASS = $config['pass'];

        $path = 'mysql:host=' . $DB_HOST . ';port=' . $DB_PORT . ';dbname=' . $DB_NAME;

        try{
            parent::__construct($path, $DB_USER, $DB_PASS);
        }
        catch(Exception $e){
                exit('Erreur de connexion à la BDD '.$DB_NAME.' sur la DB '.$DB_HOST.' avec l\'user '.$DB_USER.': '.$e->getMessage());
        }
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $this->query("SET NAMES 'utf8'");
        $ret = $this->query("USE $DB_NAME");
        if($ret === self::QUERY_FAILED){
            $ret2 = $this->query("CREATE DATABASE `$DB_NAME` /*!40100 DEFAULT CHARACTER SET utf8 */");
            if($ret2 !== self::QUERY_FAILED){
                echo 'base '.$DB_NAME.' créée';
            }
            $this->query("USE $DB_NAME");
            
        }
    }

    public static function getInstance(): MyPDO {

        if (is_null(self::$_instance)) {
            self::$_instance = new MyPDO();
        }

        return self::$_instance;
    }

    #[\ReturnTypeWillChange]
    public function query(string $query, ...$args) { //secured query with prepare and execute
        if (is_array($args[0] ?? null)) {
            $args = $args[0];
        }

        $cacheKey = $query . "::" . serialize($args);
        if (isset(MyPDO::$cache[$cacheKey])) {
            return MyPDO::$cache[$cacheKey];
        }

        $reponse = parent::prepare($query);
        $reponse->execute($args);
        $err = $reponse->errorInfo();
        if (@$err[2]) {
            if (preg_match("/Duplicate entry/", $err[2])) {
                return self::QUERY_FAILED;
            }
            error_log($err[2] . ' | ' . $query);
            return self::QUERY_FAILED;
        }

        if ($reponse->columnCount() === 0) { // a write may make cached SELECT results stale
            MyPDO::$cache = array();
        }

        $ret = array();
        while ($o = $reponse->fetch()) {
            array_push($ret, $o);
        }
        $reponse->closeCursor();
        if (count($ret)) {
            MyPDO::$cache[$cacheKey] = $ret; // on cache que les requete qui retourne des resultat, insert update doit tjrs etre executé
        }
        return $ret;
    }

}

?>
