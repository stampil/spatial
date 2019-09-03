<?php

class Joueur {

    private $bdd;
    private $email;
    private $mdp;
    
    public $id;
    public $nom;
    public $credits;
    public $etape_tuto;
    
    public static function inscrire($infos){
        $query = "INSERT INTO " . MyPDO::DB_FLAG . "joueur (nom,email,IPs,mdp,creato) VALUES(?,?,?,?,now()) ";
        MyPDO::getInstance()->query($query,$infos['nom'],$infos['email'],giveIP(),$infos['mdp']);
        // pour chaque joueur crée on crée un systeme qui sera celui de départ ( id_joueur = id_systeme départ)
        $id_joueur =  MyPDO::getInstance()->lastInsertId();
        $joueur = new Joueur($id_joueur);
        $query = "INSERT INTO " . MyPDO::DB_FLAG . "systeme (nom,creato) VALUES(?,now()) ";
        MyPDO::getInstance()->query($query,''); //nom vide a la charge du joueur de le renommer la premiere fois
        Planete::fillSysteme($joueur);
        return $joueur;
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

        
        $this->id = $ret[0]->id;
        $this->nom = $ret[0]->nom;
        $this->email = $ret[0]->email;
        $this->IPs = $ret[0]->IPs;
        $this->sur_planete= $ret[0]->sur_planete;  
        $this->credits = $ret[0]->credits;
        $this->mdp = $ret[0]->mdp;
        $this->etape_tuto= $ret[0]->etape_tuto;
        $this->creato = $ret[0]->creato;
    }
    
        public function save(){     
        $query ="UPDATE " . MyPDO::DB_FLAG . "joueur 
            set
            nom = ?,
            email =?,
            IPs = ?,
            sur_planete= ?,
            credits = ?,
            mdp =?,
            etape_tuto = ?
            WHERE id =?";
        $ret = $this->bdd->query($query,          
                $this->nom,
                $this->email,
                $this->IPs,
                $this->sur_planete,
                $this->credits,
                $this->mdp,
                $this->etape_tuto,
                $this->id
                );
    }
    
        public function goToPlanete($Planete){
            $query = "UPDATE " . MyPDO::DB_FLAG . "joueur set sur_planete=?";
            $this->bdd->query($query,$Planete->id);
    }
    
}
