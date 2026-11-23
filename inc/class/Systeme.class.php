<?php

class Systeme {

    private MyPDO $bdd;
    public int $id;
    public string $nom;
    public array $planetes;
    private static array $noms = array("Alpha","Beta","Gamma","Delta","Epsilon","Zeta","Eta","Theta","Iota","Kappa","Lamdba","Mu","Nu","Ksi","Omicron","Pi",
        "Rho","Sigma","Tau","Upsilon","Phi","Chi","Psi","Omega");

        
    public static function liste(): array {
        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "systeme ";
        $ret = MyPDO::getInstance()->query($query);
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

    private function load(int $id): static {
        
        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "systeme
                WHERE id=? ";
        $ret = $this->bdd->query($query, $id);
        if(empty($ret)){
            throw new UnexpectedValueException("Systeme n'existe pas");
        }
        
        $this->nom = $ret[0]->nom;
        $this->id = $ret[0]->id;
        $this->planetes = Planete::liste($this->id);
        return $this;
    }
    
    public function save(): void {     
        $query ="UPDATE " . MyPDO::DB_FLAG . "systeme 
            set
            nom = ?           
            WHERE id =?";
        $ret = $this->bdd->query($query,          
                $this->nom,                
                $this->id
                );
    }
    
     public static function create(): int {
        $systemes = self::liste();
        
        $Noms = array();
        foreach ($systemes as $systeme){
            
            array_push($Noms,$systeme->nom);
        }
        do{
            
            $nom = self::$noms[rand(0,count(self::$noms)-1)].'-'.rand(1000,9999);
        }while(in_array($nom, $Noms));
         
         $query = "INSERT INTO " . MyPDO::DB_FLAG . "systeme (nom,visible,creato) VALUES(?,0,now()) ";
        MyPDO::getInstance()->query($query,$nom); //nom vide a la charge du joueur de le renommer la premiere fois
        
        return MyPDO::getInstance()->lastInsertId();
         
     }
     
    public function getNom(): string {
        return '<a target="_blank" href="?p=afficheSysteme&id_systeme='.$this->id.'" class="nomSysteme">'.htmlspecialchars($this->nom).'</a>';
    }     
        
}
