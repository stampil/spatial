<?php

class Installation {

    const SQL_FILE = 'database/installation.sql';
    const LOCK_NAME = 'spatial_installation';
    const LOCK_TIMEOUT_SECONDS = 30;
    const LOCK_OBTAINED = 1;

    public static function verifier(): void {
        if(self::estInstallee()){
            return;
        }
        $bdd = MyPDO::getInstance();
        $ret = $bdd->query("SELECT GET_LOCK(?, ?) AS obtenu", self::LOCK_NAME, self::LOCK_TIMEOUT_SECONDS);
        if($ret === MyPDO::QUERY_FAILED || (int) $ret[0]->obtenu !== self::LOCK_OBTAINED){
            exit("Installation de la base en cours, réessayez dans un instant.");
        }
        if(!self::estInstallee()){
            if(self::existe('joueur')){
                exit("La base est incomplète : importer database/migration_revival.sql, ou supprimer ses tables pour une nouvelle installation.");
            }
            self::installer();
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

    private static function installer(): void {
        foreach(preg_split('/;\s*\n/', file_get_contents(self::SQL_FILE)) as $statement){
            if(trim($statement) === ''){
                continue;
            }
            if(MyPDO::getInstance()->query($statement) === MyPDO::QUERY_FAILED){
                exit("L'installation de la base a échoué : voir le journal d'erreurs du serveur.");
            }
        }
        Univers::peupler();
    }

}
