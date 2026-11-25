<?php

class Installation {

    const SQL_FILE = 'database/installation.sql';
    const MIGRATION_FILE = 'database/migration_revival.sql';
    const TABLES_ANCIENNES = array('joueur', 'perso', 'planete', 'systeme', 'production');
    const MESSAGE_DEJA_MIGREE = "Base déjà migrée : rien à faire.";
    const MESSAGE_MIGREE = "Migration terminée : anciens comptes supprimés, univers recréé.";
    const MIGRATIONS_DIR = 'database/migrations';
    const LOCK_NAME = 'spatial_installation';
    const LOCK_TIMEOUT_SECONDS = 30;
    const LOCK_OBTAINED = 1;

    public static function verifier(): void {
        if(self::estInstallee() && count(self::enAttente()) === 0){
            return;
        }
        $bdd = MyPDO::getInstance();
        self::verrouiller();
        $nouvelle = !self::estInstallee();
        if($nouvelle){
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

    public static function ancienneBase(): bool {
        return !self::estInstallee() && self::existe('joueur');
    }

    public static function migrer(): string {
        if(!self::ancienneBase()){
            return self::MESSAGE_DEJA_MIGREE;
        }
        $bdd = MyPDO::getInstance();
        self::verrouiller();
        if(!self::ancienneBase()){
            $bdd->query("SELECT RELEASE_LOCK(?)", self::LOCK_NAME);
            return self::MESSAGE_DEJA_MIGREE;
        }
        self::executer(self::MIGRATION_FILE);
        foreach(self::TABLES_ANCIENNES as $table){
            if($bdd->query("TRUNCATE TABLE " . MyPDO::DB_FLAG . $table) === MyPDO::QUERY_FAILED){
                exit("La migration a échoué en vidant la table " . $table . " : voir le journal d'erreurs du serveur.");
            }
        }
        Univers::peupler();
        $bdd->query("SELECT RELEASE_LOCK(?)", self::LOCK_NAME);
        return self::MESSAGE_MIGREE;
    }

    private static function verrouiller(): void {
        $ret = MyPDO::getInstance()->query("SELECT GET_LOCK(?, ?) AS obtenu", self::LOCK_NAME, self::LOCK_TIMEOUT_SECONDS);
        if($ret === MyPDO::QUERY_FAILED || (int) $ret[0]->obtenu !== self::LOCK_OBTAINED){
            exit("Installation de la base en cours, réessayez dans un instant.");
        }
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
