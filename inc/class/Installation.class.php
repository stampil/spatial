<?php

class Installation {

    const SQL_FILE = 'database/installation.sql';
    const MIGRATIONS_DIR = 'database/migrations';
    const LOCK_NAME = 'spatial_installation';
    const LOCK_TIMEOUT_SECONDS = 30;
    const LOCK_OBTAINED = 1;

    public static function verifier(): void {
        if(self::estInstallee() && count(self::enAttente()) === 0){
            return;
        }
        $bdd = MyPDO::getInstance();
        $ret = $bdd->query("SELECT GET_LOCK(?, ?) AS obtenu", self::LOCK_NAME, self::LOCK_TIMEOUT_SECONDS);
        if($ret === MyPDO::QUERY_FAILED || (int) $ret[0]->obtenu !== self::LOCK_OBTAINED){
            exit("Installation de la base en cours, réessayez dans un instant.");
        }
        $nouvelle = !self::estInstallee();
        if($nouvelle){
            if(self::existe('joueur')){
                exit("La base est incomplète : importer database/migration_revival.sql, ou supprimer ses tables pour une nouvelle installation.");
            }
            self::executer(self::SQL_FILE);
        }
        foreach(self::enAttente() as $version){
            self::executer(self::MIGRATIONS_DIR . '/' . $version . '.sql');
            $bdd->query("INSERT INTO " . MyPDO::DB_FLAG . "schema_migrations (version) VALUES (?)", $version);
        }
        if($nouvelle){
            Univers::peupler();
        }
        $bdd->query("SELECT RELEASE_LOCK(?)", self::LOCK_NAME);
    }

    private static function existe(string $table): bool {
        return !empty(MyPDO::getInstance()->query("SHOW TABLES LIKE ?", MyPDO::DB_FLAG . $table));
    }

    private static function estInstallee(): bool {
        $ret = MyPDO::getInstance()->query("SELECT id FROM " . MyPDO::DB_FLAG . "camp LIMIT 1");
        return $ret !== MyPDO::QUERY_FAILED && !empty($ret);
    }

    private static function enAttente(): array {
        $appliquees = array();
        $ret = MyPDO::getInstance()->query("SELECT version FROM " . MyPDO::DB_FLAG . "schema_migrations");
        if($ret === MyPDO::QUERY_FAILED){
            MyPDO::getInstance()->query("CREATE TABLE IF NOT EXISTS " . MyPDO::DB_FLAG . "schema_migrations (version varchar(64) NOT NULL, PRIMARY KEY (version)) ENGINE=InnoDB DEFAULT CHARSET=utf8");
        }
        else{
            foreach($ret as $ligne){
                array_push($appliquees, $ligne->version);
            }
        }
        $enAttente = array();
        foreach(glob(self::MIGRATIONS_DIR . '/*.sql') as $fichier){
            $version = basename($fichier, '.sql');
            if(!in_array($version, $appliquees, true)){
                array_push($enAttente, $version);
            }
        }
        sort($enAttente);
        return $enAttente;
    }

    private static function executer(string $fichier): void {
        foreach(preg_split('/;\s*\n/', file_get_contents($fichier)) as $statement){
            if(trim($statement) === ''){
                continue;
            }
            if(MyPDO::getInstance()->query($statement) === MyPDO::QUERY_FAILED){
                exit("L'installation de la base a échoué sur " . $fichier . " : voir le journal d'erreurs du serveur.");
            }
        }
    }

}
