<?php

class Joueur {

    private $bdd;
    private $email;
    private $mdp;
    
    public $creato;
    public $lastco;
    public $id;
    public $nom;
    public $credits;
    public $etape_tuto;

    public $IPs;
    
    private static $credits_depart = 500;
    

    
    public static function inscrire($infos){
        $query = "INSERT INTO " . MyPDO::DB_FLAG . "joueur (nom,email,IPs,mdp,credits,creato,lastco) VALUES(?,?,?,?,?,now(),now()) ";
        MyPDO::getInstance()->query($query,$infos['nom'],$infos['email'],giveIP(), $infos['mdp'],self::$credits_depart);
        // pour chaque joueur crée on crée un systeme qui sera celui de départ ( id_joueur = id_systeme départ)
        $id_joueur =  MyPDO::getInstance()->lastInsertId();
        $joueur = new Joueur($id_joueur);
        $id_systeme = Systeme::create();
        $systeme = new Systeme($id_systeme);
        $id_perso = Perso::create($joueur);
        $perso = new Perso($id_perso);
        Planete::fillSysteme($perso,$systeme);
        
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

        $this->credits = $ret[0]->credits;
        $this->mdp = $ret[0]->mdp;
        $this->etape_tuto= $ret[0]->etape_tuto;
        $this->creato = $ret[0]->creato;
        $this->lastco = $ret[0]->lastco;

    }
    
        public function save(){     
        $query ="UPDATE " . MyPDO::DB_FLAG . "joueur 
            set
            nom = ?,
            email =?,
            IPs = ?,
            credits = ?,
            mdp =?,
            etape_tuto = ?,
            lastco = ?
            WHERE id =?";
        $ret = $this->bdd->query($query,          
                $this->nom,
                $this->email,
                $this->IPs,
                $this->credits,
                $this->mdp,
                $this->etape_tuto,
                $this->lastco,
                $this->id
                );
    }
    

    public function giveMainPerso(){
        $query = "SELECT id FROM " . MyPDO::DB_FLAG . "perso WHERE nom=? AND id_joueur=?";
        $ret = $this->bdd->query($query,          
                $this->nom,
                $this->id
        );
        return new Perso($ret[0]->id);
    
    }
    public function getNom(){
        return '<a target="_blank" href="?p=afficheJoueur&id_joueur='.$this->id.'" class="nomJoueur">'.$this->nom.'</a>';
    }  
       
}
