<?php

class Planete {

    private $bdd;
    public $id;
    public $nom;
    public $slot;
    public $perc_revolte;
        
    public static function liste(){
        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "planete ";
        $ret = MyPDO::getInstance()->query($query);
        return $ret;
    }

    /**
     * __construct
     * @param type $id_planet
     * @throws UnexpectedValueException
     */
    public function __construct() {
        $this->bdd = MyPDO::getInstance();
    }

    public function load($id) {
        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "planete
                WHERE id=? ";
        $ret = $this->bdd->query($query, $id);

        $this->nom = $ret[0]->nom;
        $this->id = $ret[0]->id;
        $this->perc_revolte = $ret[0]->perc_revolte;
        $this->slot = $ret[0]->slot;

    }
    
}
