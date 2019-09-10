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
    public $date;
    public $fin_diplomatie;
    public $fin_voyage_planetaire;

    

    

    
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
        $this->date = new DateTime('NOW');
    }

    public function load($id) {
        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "perso
                WHERE id=? ";
        $ret = $this->bdd->query($query, $id);
        $planete = null;
        if(empty($ret[0])){
            exit("Perso n'existe pas");
        }
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
        $this->fin_diplomatie = $ret[0]->fin_diplomatie;  
        $this->fin_voyage_planetaire = $ret[0]->fin_voyage_planetaire;  
        $this->creato = $ret[0]->creato;
        $this->sur_systeme = new Systeme($planete->id_systeme);
    }
    
        public function save(){     
        $query ="UPDATE " . MyPDO::DB_FLAG . "perso 
            set
            id_joueur = ?,
            nom =?,
            PV = ?,
            FO= ?,
            diplomatie = ?,
            sur_planete =?,
            fin_diplomatie=?,
            fin_voyage_planetaire=?
            WHERE id =?";
        $ret = $this->bdd->query($query,          
                $this->joueur->id,
                $this->nom,
                $this->PV,
                $this->FO,
                $this->diplomatie,
                $this->sur_planete->id,
                $this->fin_diplomatie,
                $this->fin_voyage_planetaire,
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
    
    public function getDiplomatie(){
        return $this->getStats($this->diplomatie);
    }
    public function setDiplomatie(){
        $diplomatie = $this->diplomatie;
        $Planete = new Planete($this->sur_planete->id);
        $dateValable = clone $this->date;
        $dateValable->modify('+10 second');
        $this->fin_diplomatie = $dateValable->format('Y-m-d H:i:s');
        $this->save();
    }
    
    public function getFO(){
        return $this->getStats($this->FO);
    }
    
     public function getPV(){
        return $this->getStats($this->PV);
    }   
    
    
    private function getStats($stat){
        return $stat; // desactive l'affichage pour le dev
        $ret = '<span class="star">';
        for($i=0;$i<$stat;$i++){
            $ret.='★';
        }
        for($i=$stat;$i<5;$i++){
            $ret.='☆';
        }
        $ret.='</span>';
        return $ret;
    }
    
    
    
}
