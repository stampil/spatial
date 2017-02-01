<?php

class Joueur {

    private $bdd;
    public $id;
    public $nom;
    private $email;
    private $mdp;
    
    public static function inscrire($infos){
        $query = "INSERT INTO " . MyPDO::DB_FLAG . "joueur (nom,email,mdp,creato) VALUES(?,?,?,now()) ";
        MyPDO::getInstance()->query($query,$infos['nom'],$infos['email'],$infos['mdp']);
        return MyPDO::getInstance()->lastInsertId();;
    }
    
    public static function connecter($infos){
        $query = "select * from " . MyPDO::DB_FLAG . "joueur where email=? and mdp=? ";
        $ret = MyPDO::getInstance()->query($query,$infos['email'],$infos['mdp']);
        return @$ret[0];
    }
        
    public static function liste(){
        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "joueur ";
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
                FROM " . MyPDO::DB_FLAG . "joueur
                WHERE id=? ";
        $ret = $this->bdd->query($query, $id);

        $this->nom = $ret[0]->nom;
        $this->id = $ret[0]->id;
        $this->email = $ret[0]->email;
        $this->mdp = $ret[0]->mdp;

    }
    
    
    
}
