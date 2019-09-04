<?php

class Perso {

    private $bdd;   
    
    public $creato;  
    public $id;
    public $nom;
    public $sur_planete; 
    public $sur_systeme;
    public $joueur;
    public $PV;
    public $FO;
    public $diplomatie;

    

    
    public static function create($Joueur){
        $query = "INSERT INTO " . MyPDO::DB_FLAG . "perso (id_joueur,nom,PV,FO,diplomatie,sur_planete,creato) VALUES(?,?,?,?,?,?,now()) ";
        MyPDO::getInstance()->query($query,$Joueur->id,$Joueur->nom,rand(1,5),rand(1,5),rand(1,5),0);

        $id_perso =  MyPDO::getInstance()->lastInsertId();
        
        return $id_perso;
    }
    
   
    

        
    public static function liste($Joueur){
        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "perso where id_joueur=? ";
        $ret = MyPDO::getInstance()->query($query,$Joueur->id);
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
                FROM " . MyPDO::DB_FLAG . "perso
                WHERE id=? ";
        $ret = $this->bdd->query($query, $id);
        $planete = null;
        if($ret[0]->sur_planete){
            $planete = new Planete($ret[0]->sur_planete);
        }
        $joueur = new Joueur($ret[0]->id_joueur);
        
        $this->id = $ret[0]->id;
        $this->joueur = $joueur;
        $this->nom = $ret[0]->nom;
        $this->PV = $ret[0]->PV;
        $this->FO = $ret[0]->FO;
        $this->diplomatie= $ret[0]->diplomatie;  
        $this->sur_planete = $planete;
        $this->creato = $ret[0]->creato;
        $this->sur_systeme = new Systeme($planete->id_systeme);
    }
    
        public function save(){     
        $query ="UPDATE " . MyPDO::DB_FLAG . "perso 
            set
            id_joueur = ?,
            nom =?,
            vie = ?,
            force= ?,
            diplomatie = ?,
            sur_planete =?,
            WHERE id =?";
        $ret = $this->bdd->query($query,          
                $this->joueur->id,
                $this->nom,
                $this->PV,
                $this->FO,
                $this->diplomatie,
                $this->sur_planete->id,
                $this->id
                );
    }
    
        public function goToPlanete($Planete){
            $query = "UPDATE " . MyPDO::DB_FLAG . "perso set sur_planete=?";
            $this->bdd->query($query,$Planete->id);
    }
    
    public function getNom(){
        return '<span class="nomPerso">'.$this->nom.'</span>';
    }  
    
}
