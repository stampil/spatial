<?php

class Perso {

    const MAX_STARS = 5;
    const DEMIS_PAR_ETOILE = 2;
    const NB_CARACTERISTIQUES = 3;
    const POINTS_REPARTIS = 10;
    const DIPLOMATIE_SECONDS = 120;
    const ACTION_CONQUETE = 1;
    const ACTION_APAISER = 2;
    const MAX_PERCENT = 100;
    const SUCCESS_BASE_PERCENT = 50;
    const SUCCESS_PER_DIPLOMATIE_PERCENT = 10;
    const PRISON_SHARE_PERCENT = 75;
    const PRISON_SECONDS = 600;
    const RESPAWN_SECONDS = 1800;
    const APAISER_PER_DIPLOMATIE = 5;
    const SECONDS_PER_MINUTE = 60;
    const ROLE_DIPLOMATE = 'diplomate';
    const ROLE_HEROS = 'heros';
    const ROLE_ASSASSIN = 'assassin';
    const ROLES = array(
        self::ROLE_DIPLOMATE => array('nom' => 'Diplomate', 'icone' => 'diplomatie', 'prix' => 100),
        self::ROLE_HEROS => array('nom' => 'Héros', 'icone' => 'heros', 'prix' => 300),
        self::ROLE_ASSASSIN => array('nom' => 'Assassin', 'icone' => 'assassin', 'prix' => 200),
    );
    const PRENOMS = array('Aldric', 'Maëlle', 'Soren', 'Ysolde', 'Corentin', 'Naëlle', 'Matthias', 'Liora', 'Evander', 'Thaïs', 'Kaelen', 'Ondine',
        'Raphaël', 'Isaure', 'Dorian', 'Mirelle', 'Tancrède', 'Séléné', 'Lazare', 'Anouk', 'Baptiste', 'Cassia', 'Gaspard', 'Elowen',
        'Ivar', 'Marguerite', 'Octave', 'Nolwenn', 'Valentin', 'Zoé', 'Hector', 'Calista', 'Orlan', 'Perrine', 'Silas', 'Tiphaine');
    const NOMS = array('Valcourt', 'Marenne', 'Dorsay', 'Kestrel', 'Blanchefort', 'Orsini', 'Vasseur', 'Delorme', 'Thorne', 'Quenet', 'Ravel', 'Montclar',
        'Sauvageot', 'Lemaire', 'Ashgrove', 'Varga', 'Beaulieu', 'Okafor', 'Reyes', 'Lindqvist', 'Petrov', 'Haddad', 'Castellane', 'Morvan',
        'Aubrac', 'Delacroix-Ney', 'Faucheux', 'Grimaldi', 'Hallewyn', 'Ibarra', 'Jourdan', 'Kovacs', 'Lacombe', 'Maillard', 'Nerval', 'Tavernier');
    const ETAT_MORT = 'mort';
    const ETAT_PRISON = 'prison';
    const ETAT_VOYAGE = 'voyage';
    const ETAT_EMBARQUE = 'embarque';
    const ETAT_MISSION = 'mission';
    const ETAT_LIBRE = 'libre';
    const ETATS = array(
        self::ETAT_MORT => array('libelle' => 'Tué', 'variante' => 'alerte'),
        self::ETAT_PRISON => array('libelle' => 'Emprisonné', 'variante' => 'alerte'),
        self::ETAT_VOYAGE => array('libelle' => 'En voyage', 'variante' => ''),
        self::ETAT_EMBARQUE => array('libelle' => 'Embarqué', 'variante' => ''),
        self::ETAT_MISSION => array('libelle' => 'En mission', 'variante' => ''),
        self::ETAT_LIBRE => array('libelle' => 'Libre', 'variante' => 'ok'),
    );
    const CARACTERISTIQUE_FORCE = 'FO';
    const CARACTERISTIQUE_DIPLOMATIE = 'diplomatie';
    const HEROS_SUCCESS_PER_STAR_PERCENT = 5;
    const ASSASSINAT_BASE_PERCENT = 50;
    const ASSASSINAT_PER_STAR_PERCENT = 10;
    const ASSASSINAT_MIN_PERCENT = 10;
    const ASSASSINAT_MAX_PERCENT = 90;
    const SABOTAGE_BASE_PERCENT = 40;
    const PENALITE_ORBITE_PAR_VAISSEAU_POURCENT = 10;
    const PENALITE_ORBITE_MAX_POURCENT = 50;

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
    public ?string $fin_prison;
    public ?string $fin_mort;
    public ?int $action_diplomatie;
    public string $role;
    public bool $embarque;
    public ?int $id_destination;
    public ?string $arrivee;

    

    

    
    public static function demiEtoilesAleatoires(): array {
        $demisParCaracteristique = self::MAX_STARS * self::DEMIS_PAR_ETOILE;
        $places = range(0, self::NB_CARACTERISTIQUES * $demisParCaracteristique - 1);
        shuffle($places);
        $demiEtoiles = array_fill(0, self::NB_CARACTERISTIQUES, 0);
        foreach(array_slice($places, 0, self::POINTS_REPARTIS * self::DEMIS_PAR_ETOILE) as $place){
            $demiEtoiles[intdiv($place, $demisParCaracteristique)]++;
        }
        return $demiEtoiles;
    }

    public static function etoilesTexte(int $demiEtoiles): string {
        return $demiEtoiles % self::DEMIS_PAR_ETOILE === 0 ? (string) intdiv($demiEtoiles, self::DEMIS_PAR_ETOILE) : number_format($demiEtoiles / self::DEMIS_PAR_ETOILE, 1, ',', '');
    }

    public static function create(Joueur $Joueur): int {
        $query = "INSERT INTO " . MyPDO::DB_FLAG . "perso (id_joueur,nom,PV,FO,diplomatie,sur_planete,creato) VALUES(?,?,?,?,?,?,now()) ";
        MyPDO::getInstance()->query($query,...array_merge(array($Joueur->id,$Joueur->nom),self::demiEtoilesAleatoires(),array(0)));

        $id_perso =  MyPDO::getInstance()->lastInsertId();
        
        return $id_perso;
    }
    
   
    

        
    public static function recruter(Joueur $Joueur, Planete $Planete, string $role): int {
        $nom = self::nomAleatoire($Joueur);
        $query = "INSERT INTO " . MyPDO::DB_FLAG . "perso (id_joueur,nom,role,PV,FO,diplomatie,sur_planete,creato) VALUES(?,?,?,?,?,?,?,now()) ";
        MyPDO::getInstance()->query($query,...array_merge(array($Joueur->id,$nom,$role),self::demiEtoilesAleatoires(),array($Planete->id)));
        return MyPDO::getInstance()->lastInsertId();
    }

    private static function nomAleatoire(Joueur $Joueur): string {
        $pris = array($Joueur->nom);
        foreach(self::liste($Joueur) as $ligne){
            array_push($pris, $ligne->nom);
        }
        do{
            $nom = self::PRENOMS[random_int(0, count(self::PRENOMS) - 1)].' '.self::NOMS[random_int(0, count(self::NOMS) - 1)];
        }while(in_array($nom, $pris, true));
        return $nom;
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
            throw new UnexpectedValueException("Ce perso n'existe pas");
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
        $this->fin_prison = $ret[0]->fin_prison;
        $this->fin_mort = $ret[0]->fin_mort;
        $this->action_diplomatie = $ret[0]->action_diplomatie;
        $this->role = $ret[0]->role;
        $this->embarque = (bool) $ret[0]->embarque;
        $this->id_destination = $ret[0]->id_destination;
        $this->arrivee = $ret[0]->arrivee;
        $this->creato = $ret[0]->creato;
        
    }
    
        public function goToPlanete(Planete $Planete): void {
            $query = "UPDATE " . MyPDO::DB_FLAG . "perso set sur_planete=? WHERE id=?";
            $this->bdd->query($query,$Planete->id,$this->id);
    }
    
    public function getRole(): string {
        return self::ROLES[$this->role]['nom'];
    }

    public static function boostHeros(Joueur $joueur, Planete $planete, string $caracteristique, int $pourcentParEtoile): int {
        $query = "SELECT id FROM " . MyPDO::DB_FLAG . "perso WHERE id_joueur=? AND sur_planete=? AND role=? AND fin_mort IS NULL AND embarque=0 AND arrivee IS NULL AND (fin_prison IS NULL OR fin_prison<=?)";
        $boost = 0;
        foreach(MyPDO::getInstance()->query($query,$joueur->id,$planete->id,self::ROLE_HEROS,Tools::now()) as $ligne){
            $boost = max($boost, (new Perso($ligne->id))->$caracteristique);
        }
        return intdiv($boost * $pourcentParEtoile, self::DEMIS_PAR_ETOILE);
    }

    public static function ennemis(Joueur $joueur, Planete $planete): array {
        $query = "SELECT id FROM " . MyPDO::DB_FLAG . "perso WHERE sur_planete=? AND id_joueur<>? AND fin_mort IS NULL AND arrivee IS NULL ORDER BY id";
        return MyPDO::getInstance()->query($query,$planete->id,$joueur->id);
    }

    public function chanceAssassinat(Perso $cible): int {
        $chance = self::ASSASSINAT_BASE_PERCENT + intdiv(self::ASSASSINAT_PER_STAR_PERCENT * ($this->FO - $cible->PV), self::DEMIS_PAR_ETOILE);
        return max(self::ASSASSINAT_MIN_PERCENT, min($chance, self::ASSASSINAT_MAX_PERCENT));
    }

    public function assassiner(Perso $cible): ?string {
        if($this->role !== self::ROLE_ASSASSIN){
            return "Seul un assassin peut assassiner.";
        }
        if(!$this->canAct()){
            return 'Ce perso ne peut pas agir maintenant.';
        }
        if($cible->joueur->id === $this->joueur->id){
            return 'Impossible de viser un perso de votre camp.';
        }
        if($cible->sur_planete->id !== $this->sur_planete->id || $cible->arrivee !== null){
            return "La cible n'est pas sur cette planète.";
        }
        if($cible->fin_mort !== null){
            return 'La cible est déjà morte.';
        }
        if(random_int(1, self::MAX_PERCENT) > $this->chanceAssassinat($cible)){
            $this->fail();
            return null;
        }
        $query = "UPDATE " . MyPDO::DB_FLAG . "perso SET fin_mort=?, action_diplomatie=NULL, fin_diplomatie=NULL, embarque=0 WHERE id=? AND fin_mort IS NULL";
        $this->bdd->query($query,Tools::nowPlus(self::RESPAWN_SECONDS),$cible->id);
        if($this->bdd->lastRowCount !== 1){
            return 'La cible a été tuée entre-temps.';
        }
        Tools::addMessage($this->nom.' a assassiné '.$cible->nom.' de '.$cible->joueur->nom.'.');
        return null;
    }

    public function chanceAssassinatGouverneur(Planete $planete): int {
        $chance = self::ASSASSINAT_BASE_PERCENT + intdiv(self::ASSASSINAT_PER_STAR_PERCENT * ($this->FO - $planete->pnj_endurance * self::DEMIS_PAR_ETOILE), self::DEMIS_PAR_ETOILE) - $planete->penaliteOrbite();
        return max(self::ASSASSINAT_MIN_PERCENT, min($chance, self::ASSASSINAT_MAX_PERCENT));
    }

    public function assassinerGouverneur(): ?string {
        $erreur = $this->verifierEspion();
        if($erreur !== null){
            return $erreur;
        }
        $planete = new Planete($this->sur_planete->id);
        if($planete->pnj_endurance === null){
            return "Cette planète n'a pas de gouverneur.";
        }
        if(random_int(1, self::MAX_PERCENT) > $this->chanceAssassinatGouverneur($planete)){
            $this->fail();
            return null;
        }
        $this->bdd->query("UPDATE " . MyPDO::DB_FLAG . "planete SET pnj_endurance=NULL WHERE id=? AND pnj_endurance IS NOT NULL",$planete->id);
        if($this->bdd->lastRowCount !== 1){
            return 'Le gouverneur a été éliminé entre-temps.';
        }
        Tools::addMessage($this->nom.' a assassiné le gouverneur de '.$planete->nom.' : la planète n\'a plus de gouverneur.');
        return null;
    }

    public function chanceSabotage(Planete $planete): int {
        $chance = self::SABOTAGE_BASE_PERCENT + intdiv(self::ASSASSINAT_PER_STAR_PERCENT * $this->FO, self::DEMIS_PAR_ETOILE) - $planete->penaliteOrbite();
        return max(self::ASSASSINAT_MIN_PERCENT, min($chance, self::ASSASSINAT_MAX_PERCENT));
    }

    public function saboter(string $batiment): ?string {
        $erreur = $this->verifierEspion();
        if($erreur !== null){
            return $erreur;
        }
        if(!isset(Batiment::CATALOGUE[$batiment])){
            return 'Bâtiment inconnu.';
        }
        $planete = new Planete($this->sur_planete->id);
        if($planete->gouverneur === $this->joueur->id){
            return 'Impossible de saboter vos propres bâtiments.';
        }
        if($planete->nombre($batiment) < 1){
            return "Ce bâtiment n'existe pas sur cette planète.";
        }
        if(random_int(1, self::MAX_PERCENT) > $this->chanceSabotage($planete)){
            $this->fail();
            return null;
        }
        $this->bdd->query("UPDATE " . MyPDO::DB_FLAG . "planete SET $batiment=$batiment-1 WHERE id=? AND $batiment>0",$planete->id);
        if($this->bdd->lastRowCount !== 1){
            return 'Le bâtiment a été détruit entre-temps.';
        }
        Tools::addMessage($this->nom.' a saboté : '.Batiment::CATALOGUE[$batiment]['nom'].' détruit sur '.$planete->nom.'.');
        return null;
    }

    private function verifierEspion(): ?string {
        if($this->role !== self::ROLE_ASSASSIN){
            return "Seul un assassin peut mener cette action.";
        }
        if(!$this->canAct()){
            return 'Ce perso ne peut pas agir maintenant.';
        }
        return null;
    }

    public function getNom(): string {
        return '<a href="?p=affichePerso&id='.$this->id.'" class="nomPerso">'.htmlspecialchars($this->nom).'</a> '.Camp::libelle($this->joueur->id_camp);
    }  
    
    public function etat(): string {
        if($this->fin_mort !== null){
            return self::ETAT_MORT;
        }
        if($this->isImprisoned()){
            return self::ETAT_PRISON;
        }
        if($this->arrivee !== null){
            return self::ETAT_VOYAGE;
        }
        if($this->embarque){
            return self::ETAT_EMBARQUE;
        }
        if($this->action_diplomatie !== null){
            return self::ETAT_MISSION;
        }
        return self::ETAT_LIBRE;
    }

    public function isImprisoned(): bool {
        return !Tools::isFinCompteRebours($this->fin_prison);
    }

    public function canAct(): bool {
        return $this->fin_mort === null && !$this->isImprisoned() && $this->action_diplomatie === null && !$this->embarque && $this->arrivee === null;
    }

    public static function placesLibres(Joueur $joueur, Planete $planete): int {
        $transports = Vaisseau::stock($planete->id, $joueur->id)[Vaisseau::TRANSPORT_PERSO] ?? 0;
        $query = "SELECT COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "perso WHERE id_joueur=? AND sur_planete=? AND embarque=1";
        return $transports * Vaisseau::CAPACITE_TRANSPORT_PERSO - (int) MyPDO::getInstance()->query($query,$joueur->id,$planete->id)[0]->nombre;
    }

    public static function embarques(Joueur $joueur, Planete $planete): array {
        $query = "SELECT id FROM " . MyPDO::DB_FLAG . "perso WHERE id_joueur=? AND sur_planete=? AND embarque=1 ORDER BY id";
        return MyPDO::getInstance()->query($query,$joueur->id,$planete->id);
    }

    public static function emmener(Joueur $joueur, Planete $depart, Planete $destination, int $places, string $arrivee): int {
        $query = "UPDATE " . MyPDO::DB_FLAG . "perso SET id_destination=?, arrivee=?, embarque=0 WHERE id_joueur=? AND sur_planete=? AND embarque=1 AND arrivee IS NULL ORDER BY id LIMIT " . $places;
        $db = MyPDO::getInstance();
        $db->query($query,$destination->id,$arrivee,$joueur->id,$depart->id);
        return $db->lastRowCount;
    }

    public function embarquer(): ?string {
        if(!$this->canAct()){
            return 'Ce perso ne peut pas embarquer maintenant.';
        }
        if(self::placesLibres($this->joueur, $this->sur_planete) < 1){
            return 'Aucune place libre dans un transport personnel sur cette planète.';
        }
        $query = "UPDATE " . MyPDO::DB_FLAG . "perso SET embarque=1 WHERE id=? AND embarque=0 AND arrivee IS NULL AND action_diplomatie IS NULL";
        $this->bdd->query($query,$this->id);
        if($this->bdd->lastRowCount !== 1){
            return 'Ce perso ne peut pas embarquer maintenant.';
        }
        return null;
    }

    public function descendre(): ?string {
        $query = "UPDATE " . MyPDO::DB_FLAG . "perso SET embarque=0 WHERE id=? AND embarque=1";
        $this->bdd->query($query,$this->id);
        if($this->bdd->lastRowCount !== 1){
            return "Ce perso n'est pas embarqué.";
        }
        return null;
    }

    public function dureeDiplomatie(): int {
        return intdiv(self::DIPLOMATIE_SECONDS * self::DEMIS_PAR_ETOILE, $this->diplomatie);
    }

    public function chanceDeSucces(Joueur $joueur): int {
        if($joueur->etape_tuto < Joueur::TUTO_END_STEP){
            return self::MAX_PERCENT;
        }
        $chance = self::SUCCESS_BASE_PERCENT + intdiv(self::SUCCESS_PER_DIPLOMATIE_PERCENT * $this->diplomatie, self::DEMIS_PAR_ETOILE);
        $chance += self::boostHeros($joueur, $this->sur_planete, self::CARACTERISTIQUE_DIPLOMATIE, self::HEROS_SUCCESS_PER_STAR_PERCENT);
        return min($chance, self::MAX_PERCENT);
    }

    public function startDiplomatie(int $action): ?string {
        if($action !== self::ACTION_CONQUETE && $action !== self::ACTION_APAISER){
            return 'Action de diplomatie inconnue.';
        }
        if($this->role !== self::ROLE_DIPLOMATE){
            return 'Seul un diplomate mène une diplomatie.';
        }
        if($this->fin_mort !== null){
            return 'Ce perso est mort.';
        }
        if($this->isImprisoned()){
            return 'Ce perso est emprisonné.';
        }
        if($this->embarque || $this->arrivee !== null){
            return 'Ce perso est embarqué ou en voyage.';
        }
        if($action === self::ACTION_CONQUETE && $this->sur_planete->gouverneur !== 0){
            return 'Cette planète a déjà un gouverneur.';
        }
        if($action === self::ACTION_CONQUETE && $this->sur_planete->pnj_endurance !== null){
            return 'Un gouverneur est en place : un assassin doit d\'abord l\'éliminer.';
        }
        if($action === self::ACTION_CONQUETE && $this->sur_planete->id_camp !== null){
            return 'Cette planète est tenue par '.(new Camp($this->sur_planete->id_camp))->nom.'.';
        }
        if($action === self::ACTION_APAISER && ($this->sur_planete->gouverneur !== $this->joueur->id || $this->sur_planete->perc_revolte === 0)){
            return "Cette planète n'a pas besoin d'être apaisée.";
        }
        $query = "UPDATE " . MyPDO::DB_FLAG . "perso SET action_diplomatie=?, fin_diplomatie=? WHERE id=? AND action_diplomatie IS NULL";
        $this->bdd->query($query,$action,Tools::nowPlus($this->dureeDiplomatie()),$this->id);
        if($this->bdd->lastRowCount !== 1){
            return 'Une diplomatie est déjà en cours.';
        }
        return null;
    }

    public function resolve(Joueur $joueur): void {
        if($this->fin_mort !== null && Tools::isFinCompteRebours($this->fin_mort)){
            $this->respawn();
        }
        elseif($this->arrivee !== null && Tools::isFinCompteRebours($this->arrivee)){
            $this->arriver();
        }
        elseif($this->action_diplomatie !== null && Tools::isFinCompteRebours($this->fin_diplomatie)){
            $this->finishDiplomatie($joueur);
        }
    }

    private function arriver(): void {
        $query = "UPDATE " . MyPDO::DB_FLAG . "perso SET sur_planete=id_destination, id_destination=NULL, arrivee=NULL WHERE id=? AND arrivee IS NOT NULL";
        $this->bdd->query($query,$this->id);
        if($this->bdd->lastRowCount === 1){
            Tools::addMessage($this->nom.' est arrivé sur '.(new Planete($this->id_destination))->nom.'.');
        }
    }

    private function finishDiplomatie(Joueur $joueur): void {
        $action = $this->action_diplomatie;
        $query = "UPDATE " . MyPDO::DB_FLAG . "perso SET action_diplomatie=NULL, fin_diplomatie=NULL WHERE id=? AND action_diplomatie IS NOT NULL";
        $this->bdd->query($query,$this->id);
        if($this->bdd->lastRowCount !== 1){
            return;
        }
        $planete = $this->sur_planete;
        if($action === self::ACTION_APAISER){
            $planete->apaiser(intdiv($this->diplomatie * self::APAISER_PER_DIPLOMATIE, self::DEMIS_PAR_ETOILE));
            Tools::addMessage($this->nom.' a apaisé la révolte sur '.$planete->nom.'.');
            return;
        }
        if(random_int(1, self::MAX_PERCENT) > $this->chanceDeSucces($joueur)){
            $this->fail();
            return;
        }
        if(!$planete->gouverner($joueur)){
            Tools::addMessage($planete->nom.' a déjà un gouverneur : la diplomatie de '.$this->nom.' est annulée.');
            return;
        }
        Tools::addInfo('Une planète rejoint votre cause', $planete->nom.' rejoint votre camp : vous la gouvernez maintenant.', Planete::ILLUSTRATION_REJOINT, $joueur->id_camp);
        if($joueur->etape_tuto < Tuto::ETAPE_USINE){
            $joueur->etape_tuto = Tuto::ETAPE_USINE;
            $joueur->save();
            Tools::addMessage('Tutoriel : votre première planète est conquise.');
        }
    }

    private function fail(): void {
        if(random_int(1, self::MAX_PERCENT) <= self::PRISON_SHARE_PERCENT){
            $query = "UPDATE " . MyPDO::DB_FLAG . "perso SET fin_prison=? WHERE id=?";
            $this->bdd->query($query,Tools::nowPlus(self::PRISON_SECONDS),$this->id);
            Tools::addMessage($this->nom.' a échoué et a été emprisonné pendant '.intdiv(self::PRISON_SECONDS, self::SECONDS_PER_MINUTE).' '.__('minute', 'minutes', intdiv(self::PRISON_SECONDS, self::SECONDS_PER_MINUTE)).'.');
            return;
        }
        $query = "UPDATE " . MyPDO::DB_FLAG . "perso SET fin_mort=? WHERE id=?";
        $this->bdd->query($query,Tools::nowPlus(self::RESPAWN_SECONDS),$this->id);
        Tools::addMessage($this->nom.' a échoué et a été tué. Un nouveau perso arrivera dans '.intdiv(self::RESPAWN_SECONDS, self::SECONDS_PER_MINUTE).' '.__('minute', 'minutes', intdiv(self::RESPAWN_SECONDS, self::SECONDS_PER_MINUTE)).'.');
    }

    private function respawn(): void {
        $planeteId = Planete::premiereGouvernee($this->joueur->id) ?? $this->sur_planete->id;
        $query = "UPDATE " . MyPDO::DB_FLAG . "perso SET fin_mort=NULL, fin_prison=NULL, embarque=0, PV=?, FO=?, diplomatie=?, sur_planete=? WHERE id=? AND fin_mort IS NOT NULL";
        $this->bdd->query($query,...array_merge(self::demiEtoilesAleatoires(),array($planeteId,$this->id)));
        if($this->bdd->lastRowCount === 1){
            Tools::addMessage('Un nouveau perso, '.$this->nom.', est arrivé.');
        }
    }

}
