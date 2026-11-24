<?php

class Systeme {

    const CARTE_LARGEUR = 1000;
    const CARTE_HAUTEUR = 1000;
    const ECART_MINIMUM = 80;
    const VOISIN_PORTEE = 80;
    const TENTATIVES_PAR_AGRANDISSEMENT = 20;
    const AGRANDISSEMENT = 100;
    const DEGRES_TOUR = 360;
    const DISTANCE_MEME_SYSTEME = 0;
    const DISTANCE_MINIMUM = 1;
    const REQUETE_SYSTEMES_DES_ANCIENS = "SELECT p.id_systeme FROM " . MyPDO::DB_FLAG . "planete p JOIN " . MyPDO::DB_FLAG . "joueur j ON j.id=p.gouverneur WHERE j.fin_tuto<=?";

    private MyPDO $bdd;
    public int $id;
    public string $nom;
    public bool $visible;
    public int $x;
    public int $y;
    public array $planetes;
    private static array $noms = array("Alpha","Beta","Gamma","Delta","Epsilon","Zeta","Eta","Theta","Iota","Kappa","Lambda","Mu","Nu","Ksi","Omicron","Pi",
        "Rho","Sigma","Tau","Upsilon","Phi","Chi","Psi","Omega");

        
    public static function liste(): array {
        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "systeme ";
        $ret = MyPDO::getInstance()->query($query);
        return $ret;
    }

    private static function limiteAnciens(): string {
        return Tools::formatDate(time() - Joueur::VISIBILITE_APRES_TUTO_SECONDES);
    }

    public static function listeVisibles(int $joueurId): array {
        $query = "SELECT id FROM " . MyPDO::DB_FLAG . "systeme
                WHERE visible=1
                OR id IN (SELECT id_systeme FROM " . MyPDO::DB_FLAG . "decouverte WHERE id_joueur=?)
                OR id IN (" . self::REQUETE_SYSTEMES_DES_ANCIENS . ")";
        $visibles = array();
        foreach(MyPDO::getInstance()->query($query,$joueurId,self::limiteAnciens()) as $ligne){
            $systeme = new Systeme($ligne->id);
            if($systeme->estConnuDe($joueurId)){
                array_push($visibles, $systeme);
            }
        }
        return $visibles;
    }

    public static function explorables(int $joueurId): array {
        $query = "SELECT id FROM " . MyPDO::DB_FLAG . "systeme
                WHERE visible=1 OR id IN (" . self::REQUETE_SYSTEMES_DES_ANCIENS . ")";
        $explorables = array();
        foreach(MyPDO::getInstance()->query($query,self::limiteAnciens()) as $ligne){
            $systeme = new Systeme($ligne->id);
            if(!$systeme->estConnuDe($joueurId)){
                array_push($explorables, $systeme);
            }
        }
        return $explorables;
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
        $this->x = $ret[0]->x;
        $this->y = $ret[0]->y;
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
    
     public static function create(bool $visible = false, ?array $position = null): int {
        $systemes = self::liste();
        
        $Noms = array();
        foreach ($systemes as $systeme){
            
            array_push($Noms,$systeme->nom);
        }
        do{
            
            $nom = self::$noms[rand(0,count(self::$noms)-1)].'-'.rand(1000,9999);
        }while(in_array($nom, $Noms));
         
         list($x, $y) = $position ?? self::positionLibre();
         $query = "INSERT INTO " . MyPDO::DB_FLAG . "systeme (nom,visible,x,y,creato) VALUES(?,?,?,?,now()) ";
        MyPDO::getInstance()->query($query,$nom,(int) $visible,$x,$y); //nom vide a la charge du joueur de le renommer la premiere fois
        
        return MyPDO::getInstance()->lastInsertId();
         
     }
     
    public static function positionLibre(?array $ancre = null): array {
        $existantes = MyPDO::getInstance()->query("SELECT x, y FROM " . MyPDO::DB_FLAG . "systeme");
        for($tentative = 0; ; $tentative++){
            $marge = intdiv($tentative, self::TENTATIVES_PAR_AGRANDISSEMENT) * self::AGRANDISSEMENT;
            if($ancre === null){
                $x = random_int(-$marge, self::CARTE_LARGEUR + $marge);
                $y = random_int(-$marge, self::CARTE_HAUTEUR + $marge);
            }
            else{
                $angle = deg2rad(random_int(0, self::DEGRES_TOUR - 1));
                $rayon = random_int(self::ECART_MINIMUM, self::ECART_MINIMUM + self::VOISIN_PORTEE + $marge);
                $x = $ancre[0] + (int) round($rayon * cos($angle));
                $y = $ancre[1] + (int) round($rayon * sin($angle));
            }
            if(self::estLibre($existantes, $x, $y)){
                return array($x, $y);
            }
        }
    }

    private static function estLibre(array $existantes, int $x, int $y): bool {
        foreach($existantes as $ligne){
            if(hypot($ligne->x - $x, $ligne->y - $y) < self::ECART_MINIMUM){
                return false;
            }
        }
        return true;
    }

    public function distanceA(Systeme $autre): int {
        if($this->id === $autre->id){
            return self::DISTANCE_MEME_SYSTEME;
        }
        return max(self::DISTANCE_MINIMUM, (int) round(hypot($this->x - $autre->x, $this->y - $autre->y)));
    }

    public function estConnuDe(int $joueurId): bool {
        return Decouverte::existe($joueurId, $this->id) || $this->estVisibleDeTous();
    }

    private function estVisibleDeTous(): bool {
        if($this->visible){
            return !in_array($this->getIdCamp(), Camp::DISCRETS, true);
        }
        if($this->getIdCamp() !== Camp::EMPIRE){
            return false;
        }
        $query = self::REQUETE_SYSTEMES_DES_ANCIENS . " AND p.id_systeme=?";
        return !empty($this->bdd->query($query,self::limiteAnciens(),$this->id));
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
