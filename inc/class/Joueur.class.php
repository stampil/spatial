<?php

class Joueur {

    private MyPDO $bdd;
    private string $email;
    private string $mdp;
    
    public string $creato;
    public string $lastco;
    public int $id;
    public string $nom;
    public int $credits;
    public ?string $credits_maj;
    public int $etape_tuto;
    public int $id_camp;
    public ?int $id_systeme_voisin;
    public ?string $fin_tuto;

    public string $IPs;
    
    const TUTO_END_STEP = 8;
    const TUTO_DIVISEUR_DUREES = 5;
    const VISIBILITE_APRES_TUTO_SECONDES = 7 * 24 * 3600;

    private static int $credits_depart = 500;
    

    
    public static function inscrire(array $infos): ?Joueur{
        $query = "INSERT INTO " . MyPDO::DB_FLAG . "joueur (nom,email,IPs,mdp,credits,id_camp,creato,lastco) VALUES(?,?,?,?,?,?,now(),now()) ";
        $ret = MyPDO::getInstance()->query($query,$infos['nom'],$infos['email'],giveIP(), $infos['mdp'],self::$credits_depart,$infos['id_camp']);
        if($ret === MyPDO::QUERY_FAILED){
            return null;
        }
        // pour chaque joueur crée on crée un systeme qui sera celui de départ ( id_joueur = id_systeme départ)
        $id_joueur =  MyPDO::getInstance()->lastInsertId();
        $joueur = new Joueur($id_joueur);
        $id_systeme = Systeme::create();
        $systeme = new Systeme($id_systeme);
        $id_perso = Perso::create($joueur);
        $perso = new Perso($id_perso);
        Planete::fillSysteme($perso,$systeme);
        Decouverte::ajouter($joueur->id, $id_systeme);
        Univers::creerVoisin($joueur, $systeme);
        
        return $joueur;
    }
    
   
    
    public static function connecter(array $infos): ?object{
        $query = "select * from " . MyPDO::DB_FLAG . "joueur where email=? ";
        $ret = MyPDO::getInstance()->query($query,$infos['email']);
        if(empty($ret) || $ret === MyPDO::QUERY_FAILED || !password_verify($infos['mdp'], $ret[0]->mdp)){
            return null;
        }
        return $ret[0];
    }
        
    public static function liste(): array{
        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "joueur ";
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

    public function load(int $id): void {
        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "joueur
                WHERE id=? ";
        $ret = $this->bdd->query($query, $id);
        if(empty($ret)){
            throw new UnexpectedValueException("Ce joueur n'existe pas");
        }
        
        $this->id = $ret[0]->id;
        $this->nom = $ret[0]->nom;
        $this->email = $ret[0]->email;
        $this->IPs = $ret[0]->IPs;

        $this->credits = $ret[0]->credits;
        $this->credits_maj = $ret[0]->credits_maj;
        $this->mdp = $ret[0]->mdp;
        $this->etape_tuto= $ret[0]->etape_tuto;
        $this->id_camp = $ret[0]->id_camp;
        $this->id_systeme_voisin = $ret[0]->id_systeme_voisin;
        $this->fin_tuto = $ret[0]->fin_tuto;
        $this->creato = $ret[0]->creato;
        $this->lastco = $ret[0]->lastco;

    }
    
        public function save(): void {     
        $query ="UPDATE " . MyPDO::DB_FLAG . "joueur 
            set
            nom = ?,
            email =?,
            IPs = ?,
            mdp =?,
            etape_tuto = ?,
            lastco = ?
            WHERE id =?";
        $ret = $this->bdd->query($query,          
                $this->nom,
                $this->email,
                $this->IPs,
                $this->mdp,
                $this->etape_tuto,
                $this->lastco,
                $this->id
                );
    }
    

    public function revenuNet(): int {
        $revenu = 0;
        foreach(Planete::listeGouvernees($this->id) as $ligne){
            $revenu += (new Planete($ligne->id))->revenuNet();
        }
        if($revenu > 0 && $this->aBonus(Bonus::MARCHE)){
            $revenu += intdiv($revenu * Bonus::pourcent(Bonus::MARCHE), Bonus::PERCENT);
        }
        return $revenu;
    }

    public function aBonus(string $bonus): bool {
        $query = "SELECT id FROM " . MyPDO::DB_FLAG . "planete WHERE gouverneur=? AND bonus=? LIMIT 1";
        return !empty($this->bdd->query($query,$this->id,$bonus));
    }

    public function payer(int $montant): bool {
        $query = "UPDATE " . MyPDO::DB_FLAG . "joueur SET credits=credits-? WHERE id=? AND credits>=?";
        $this->bdd->query($query,$montant,$this->id,$montant);
        if($this->bdd->lastRowCount !== 1){
            return false;
        }
        $this->credits -= $montant;
        return true;
    }

    public function crediter(int $montant): void {
        $query = "UPDATE " . MyPDO::DB_FLAG . "joueur SET credits=credits+? WHERE id=?";
        $this->bdd->query($query,$montant,$this->id);
        $this->credits += $montant;
    }

    public function updateCredits(): void {
        if($this->credits_maj === null){
            $query = "UPDATE " . MyPDO::DB_FLAG . "joueur SET credits_maj=? WHERE id=? AND credits_maj IS NULL";
            $this->bdd->query($query,Tools::now(),$this->id);
            return;
        }
        $depuis = strtotime($this->credits_maj);
        $heures = intdiv(time() - $depuis, Planete::SECONDS_PER_HOUR);
        if($heures < 1){
            return;
        }
        $gain = $heures * $this->revenuNet();
        $nouvelleMaj = Tools::formatDate($depuis + $heures * Planete::SECONDS_PER_HOUR);
        $query = "UPDATE " . MyPDO::DB_FLAG . "joueur SET credits=GREATEST(credits+?,0), credits_maj=? WHERE id=? AND credits_maj=?";
        $this->bdd->query($query,$gain,$nouvelleMaj,$this->id,$this->credits_maj);
        if($this->bdd->lastRowCount === 1){
            $this->credits = max($this->credits + $gain, 0);
            $this->credits_maj = $nouvelleMaj;
        }
    }

    public function resolveEvents(): void {
        $this->updateCredits();
        foreach(Planete::listeGouvernees($this->id) as $ligne){
            (new Planete($ligne->id))->updateRevolte();
        }
        Vaisseau::resolveProduction($this);
        Vaisseau::resolveArrivees($this);
        Tuto::avancer($this);
        foreach(Perso::liste($this) as $ligne){
            (new Perso($ligne->id))->resolve($this);
        }
    }

    public function giveMainPerso(): Perso {
        $query = "SELECT id FROM " . MyPDO::DB_FLAG . "perso WHERE nom=? AND id_joueur=?";
        $ret = $this->bdd->query($query,          
                $this->nom,
                $this->id
        );
        return new Perso($ret[0]->id);
    
    }
    public function enTuto(): bool {
        return $this->etape_tuto < self::TUTO_END_STEP;
    }

    public function dureeEffective(int $secondes): int {
        if($this->enTuto()){
            return intdiv($secondes, self::TUTO_DIVISEUR_DUREES);
        }
        return $secondes;
    }

    public function prixUnite(int $prix): int {
        if($this->id_camp === Camp::EMPIRE){
            return intdiv($prix * (Camp::PERCENT - Camp::EMPIRE_REMISE_POURCENT), Camp::PERCENT);
        }
        return $prix;
    }

    public function prixVaisseau(string $type): int {
        $prix = $this->prixUnite(Vaisseau::CATALOGUE[$type]['prix']);
        foreach(Bonus::REMISES_VAISSEAU as $bonus => $typeRemise){
            if($typeRemise === $type && $this->aBonus($bonus)){
                $prix = intdiv($prix * (Bonus::PERCENT - Bonus::pourcent($bonus)), Bonus::PERCENT);
            }
        }
        return $prix;
    }

    public function peutCibler(Systeme $systeme): bool {
        return $systeme->estConnuDe($this->id) || $systeme->id === $this->id_systeme_voisin;
    }

    public function ouvrirSession(): void {
        session_regenerate_id(true);
        $_SESSION['Sid'] = $this->id;
        $ips = explode(" ",$this->IPs);
        array_push($ips, giveIP());
        $this->IPs = implode(' ', array_unique($ips));
        $this->lastco = Tools::now();
        $this->save();
    }

    public function getCamp(): Camp {
        return new Camp($this->id_camp);
    }

    public function getNom(): string {
        return '<a href="?p=afficheJoueur&id_joueur='.$this->id.'" class="nomJoueur">'.htmlspecialchars($this->nom).'</a> '.Camp::libelle($this->id_camp);
    }  
       
}
