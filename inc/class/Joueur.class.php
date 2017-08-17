<?php

class Joueur {

    private $bdd;
    public $id;
    public $nom;
    public $credits;
    private $email;
    private $mdp;
    
    public static function inscrire($infos){
        $query = "INSERT INTO " . MyPDO::DB_FLAG . "joueur (nom,email,mdp,creato) VALUES(?,?,?,now()) ";
        MyPDO::getInstance()->query($query,$infos['nom'],$infos['email'],$infos['mdp']);
        // pour chaque joueur crée on crée un systeme qui sera celui de départ ( id_joueur = id_systeme départ)
        $id_joueur =  MyPDO::getInstance()->lastInsertId();
        $query = "INSERT INTO " . MyPDO::DB_FLAG . "systeme (nom) VALUES(?) ";
        MyPDO::getInstance()->query($query,''); //nom vide a la charge du joueur de le renommer la premiere fois
        Planete::fillSysteme($id_joueur);
        return $id_joueur;
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
    public function __construct($id) {
        $this->bdd = MyPDO::getInstance();
        $this->load($id);
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
        $this->credits = $ret[0]->credits;
    }
    
        public function save(){     
        $query ="UPDATE " . MyPDO::DB_FLAG . "joueur 
            set
            nom = ?,
            credits = ?,
            email =?,
            mdp =?
            WHERE id =?";
        $ret = $this->bdd->query($query,          
                $this->nom,
                $this->credits,
                $this->email,
                $this->mdp,              
                $this->id
                );
    }
    
    
    
}
