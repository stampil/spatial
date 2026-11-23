<?php

class Perso {

    const MAX_STARS = 5;

    private MyPDO $bdd;   
    
    public string $creato;  
    public int $id;
    public string $nom;
    public ?Planete $sur_planete = null; 
    public ?Systeme $sur_systeme = null;
    public Joueur $joueur;
    public int $PV;
    public int $FO;
    public int $diplomatie;
    public ?string $fin_diplomatie;
    public ?string $fin_voyage_planetaire;

    

    

    
    public static function create(Joueur $Joueur): int {
        $query = "INSERT INTO " . MyPDO::DB_FLAG . "perso (id_joueur,nom,PV,FO,diplomatie,sur_planete,creato) VALUES(?,?,?,?,?,?,now()) ";
        MyPDO::getInstance()->query($query,$Joueur->id,$Joueur->nom,rand(1,5),rand(1,5),rand(1,5),0);

        $id_perso =  MyPDO::getInstance()->lastInsertId();
        
        return $id_perso;
    }
    
   
    

        
    public static function liste(Joueur $Joueur): array {
        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "perso where id_joueur=? ";
        $ret = MyPDO::getInstance()->query($query,$Joueur->id);
        return $ret;
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

    public function load(int $id): void {
        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "perso
                WHERE id=? ";
        $ret = $this->bdd->query($query, $id);
        $planete = null;
        if(empty($ret[0])){
            throw new UnexpectedValueException("Perso n'existe pas");
        }
        if($ret[0]->sur_planete){
            $planete = new Planete($ret[0]->sur_planete);
             $this->sur_planete = $planete;
             $this->sur_systeme = new Systeme($planete->id_systeme);
        }
        $joueur = new Joueur($ret[0]->id_joueur);
        
        $this->id = $ret[0]->id;
        $this->joueur = $joueur;
        $this->nom = $ret[0]->nom;
        $this->PV = $ret[0]->PV;
        $this->FO = $ret[0]->FO;
        $this->diplomatie= $ret[0]->diplomatie;  
       
        $this->fin_diplomatie = $ret[0]->fin_diplomatie;  
        $this->fin_voyage_planetaire = $ret[0]->fin_voyage_planetaire;  
        $this->creato = $ret[0]->creato;
        
    }
    
        public function save(): void {     
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
    
        public function goToPlanete(Planete $Planete): void {
            $query = "UPDATE " . MyPDO::DB_FLAG . "perso set sur_planete=? WHERE id=?";
            $this->bdd->query($query,$Planete->id,$this->id);
    }
    
    public function getNom(): string {
        return '<a target="_blank" href="?p=affichePerso&id='.$this->id.'" class="nomPerso">'.htmlspecialchars($this->nom).'</a>';
    }  
    
    public function getDiplomatie(): string {
        return $this->getStats($this->diplomatie);
    }
    public function setDiplomatie(): void {

        $dateValable = new DateTime('NOW');
        $dateValable->modify('+'.(2*60/$this->diplomatie).' second');
        $this->fin_diplomatie = $dateValable->format('Y-m-d H:i:s');
        $this->save();
    }
    
    public function getFO(): string {
        return $this->getStats($this->FO);
    }
    
     public function getPV(): string {
        return $this->getStats($this->PV);
    }   
    
    
    private function getStats(int $stat): string {
        $ret = '<span class="star">';
        for($i=0;$i<$stat;$i++){
            $ret.='★';
        }
        for($i=$stat;$i<self::MAX_STARS;$i++){
            $ret.='☆';
        }
        $ret.='</span>';
        return $ret;
    }
    
    
    
}
