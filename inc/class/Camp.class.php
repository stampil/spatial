<?php

class Camp {

    private MyPDO $bdd;
    public int $id;
    public string $nom;
    public string $histoire;
    public bool $jouable;

    public static function liste(): array {
        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "camp ";
        $ret = MyPDO::getInstance()->query($query);
        return $ret;
    }

    public static function listeJouables(): array {
        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "camp
                WHERE jouable=1 ";
        $ret = MyPDO::getInstance()->query($query);
        return $ret;
    }

    public static function estJouable(int $id): bool {
        foreach(self::listeJouables() as $ligne){
            if((int) $ligne->id === $id){
                return true;
            }
        }
        return false;
    }

    /**
     * __construct
     * @param int $id
     * @throws UnexpectedValueException
     */
    public function __construct(int $id) {
        $this->bdd = MyPDO::getInstance();
        $this->load($id);
    }

    private function load(int $id): void {
        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "camp
                WHERE id=? ";
        $ret = $this->bdd->query($query, $id);
        if(empty($ret)){
            throw new UnexpectedValueException("Ce camp n'existe pas");
        }

        $this->id = $ret[0]->id;
        $this->nom = $ret[0]->nom;
        $this->histoire = $ret[0]->histoire;
        $this->jouable = (bool) $ret[0]->jouable;
    }

    public function getNom(): string {
        return htmlspecialchars($this->nom);
    }

    public function adversaires(): array {
        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "camp
                WHERE id<>? ";
        $ret = $this->bdd->query($query, $this->id);
        return $ret;
    }

}
