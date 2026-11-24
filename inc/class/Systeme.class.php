<?php

class Systeme {

    private MyPDO $bdd;
    public int $id;
    public string $nom;
    public bool $visible;
    public array $planetes;
    private static array $noms = array("Alpha","Beta","Gamma","Delta","Epsilon","Zeta","Eta","Theta","Iota","Kappa","Lambda","Mu","Nu","Ksi","Omicron","Pi",
        "Rho","Sigma","Tau","Upsilon","Phi","Chi","Psi","Omega");

        
    public static function liste(): array {
        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "systeme ";
        $ret = MyPDO::getInstance()->query($query);
        return $ret;
    }

    public static function listeVisibles(int $joueurId): array {
        $query = "SELECT * FROM " . MyPDO::DB_FLAG . "systeme
                WHERE visible=1 OR id IN (SELECT id_systeme FROM " . MyPDO::DB_FLAG . "decouverte WHERE id_joueur=?)";
        return MyPDO::getInstance()->query($query,$joueurId);
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
            throw new UnexpectedValueException("Ce système n'existe pas");
        }
        
        $this->nom = $ret[0]->nom;
        $this->visible = (bool) $ret[0]->visible;
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
    
     public static function create(bool $visible = false): int {
        $systemes = self::liste();
        
        $Noms = array();
        foreach ($systemes as $systeme){
            
            array_push($Noms,$systeme->nom);
        }
        do{
            
            $nom = self::$noms[rand(0,count(self::$noms)-1)].'-'.rand(1000,9999);
        }while(in_array($nom, $Noms));
         
         $query = "INSERT INTO " . MyPDO::DB_FLAG . "systeme (nom,visible,creato) VALUES(?,?,now()) ";
        MyPDO::getInstance()->query($query,$nom,(int) $visible); //nom vide a la charge du joueur de le renommer la premiere fois
        
        return MyPDO::getInstance()->lastInsertId();
         
     }
     
    public function estConnuDe(int $joueurId): bool {
        return $this->visible || Decouverte::existe($joueurId, $this->id);
    }

    public function getIdCamp(): ?int {
        $comptes = array();
        foreach($this->planetes as $planete){
            if($planete->id_camp !== null){
                $comptes[$planete->id_camp] = ($comptes[$planete->id_camp] ?? 0) + 1;
            }
        }
        $id_camp = null;
        $maximum = 0;
        $egalite = false;
        foreach($comptes as $id => $nombre){
            if($nombre > $maximum){
                $id_camp = $id;
                $maximum = $nombre;
                $egalite = false;
            }
            elseif($nombre === $maximum){
                $egalite = true;
            }
        }
        return $egalite ? null : $id_camp;
    }

    public function getNom(): string {
        return '<a target="_blank" href="?p=afficheSysteme&id_systeme='.$this->id.'" class="nomSysteme">'.htmlspecialchars($this->nom).'</a> '.Camp::libelle($this->getIdCamp());
    }     
        
}
