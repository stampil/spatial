<?php

class Planete {

    const INITIAL_REVOLTE_MAX = 25;
    const MAX_REVOLTE = 100;
    const REVOLTE_PER_HOUR = 1;
    const SECONDS_PER_HOUR = 3600;
    const OPERATION_CONSTRUIRE = 'construire';
    const OPERATION_DEMOLIR = 'demolir';
    const MIN_PLANETES = 3;
    const MAX_PLANETES = 12;
    const IMPERIAL_REBELLES_MAX = 2;
    const IMPERIAL_PIRATES_MAX = 1;
    const SLOT_DEPART = 8;
    const BOUCLIER_PAR_SLOT = 2;
    const CANON_PAR_SLOT = 1;
    const BOUCLIER_PAR_STRUCTURE = 2;
    const CANON_PAR_STRUCTURE = 1;
    const STRUCTURES_IA_SLOT_MINIMUM = 6;
    const GOUVERNEUR_POURCENT = 75;
    const GOUVERNEUR_NEUTRE_POURCENT = 50;
    const GOUVERNEUR_ETOILES_MAX = 3;
    const MALUS_OCCUPATION_POURCENT = 20;
    const TROUPES_DIVISEUR_SLOT = 2;
    const CAPTURE_REVOLTE = 50;
    const REVOLTE_ALERTE = 40;
    const SEUIL_INDEPENDANCE = 51;
    const TEXTURES = 47;
    const ILLUSTRATION_REJOINT = 'evenement-rejoint';
    const ILLUSTRATION_REVOLTE = 'evenement-revolte';
    const ILLUSTRATION_CONQUISE = 'evenement-conquise';
    const REGENERATION_POURCENT_PAR_HEURE = 10;
    const REGENERATION_MINIMUM_PAR_HEURE = 1;
    const FLOTTE_EMPIRE_PAR_SLOT = 1;
    const REFUGES_CANDIDATS = 12;
    const FLOTTE_PIRATES_DIVISEUR_SLOT = 2;
    const FLOTTE_PIRATES_MINIMUM = 1;
    const TROUPES_PAR_HEURE = 1;
    const TROUPES_DE_BASE_MINIMUM = 1;
    const PERCENT = 100;

    private MyPDO $bdd;
    public int $id;
    public string $nom;
    public int $slot;
    public int $perc_revolte;
    public int $gouverneur;
    public int $id_systeme;
    public int $nb_usine_vaiss_leger;
    public int $nb_usine_vaiss_moyen;
    public int $nb_usine_vaiss_lourd;
    public int $nb_usine_ressource;
    public int $nb_academie;
    public ?string $revolte_maj;
    public ?int $id_camp;
    public int $bouclier;
    public int $canon;
    public int $troupes;
    public ?string $bonus;
    public ?string $defense_maj;
    public int $flotte_ia;
    public int $nb_bouclier_planetaire;
    public int $nb_canon_orbital;
    public ?int $pnj_endurance;
    public bool $occupee;
    public ?string $detournement_fin;
    public Systeme $systeme;
    public static int $min_slot = 1;
    public static int $max_slot = 16;
    
        
    public static function liste(?int $id_systeme=null): array{
        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "planete ";
        if($id_systeme){
            $query .= " WHERE id_systeme=?";
            return MyPDO::getInstance()->query($query,$id_systeme);
        }
        return MyPDO::getInstance()->query($query);
    }
    
    
    public static function listeVisibles(int $joueurId): array{
        $systemes = array();
        foreach(Systeme::listeVisibles($joueurId) as $systeme){
            array_push($systemes, $systeme->id);
        }
        if(count($systemes) === 0){
            return array();
        }
        $marques = implode(',', array_fill(0, count($systemes), '?'));
        $query = "SELECT * FROM " . MyPDO::DB_FLAG . "planete WHERE id_systeme IN ($marques)";
        return MyPDO::getInstance()->query($query,$systemes);
    }

    public static function listeGouvernees(int $joueurId): array{
        $query = "SELECT id FROM " . MyPDO::DB_FLAG . "planete WHERE gouverneur=?";
        return MyPDO::getInstance()->query($query,$joueurId);
    }

    public static function premiereGouvernee(int $joueurId): ?int{
        $query = "SELECT id FROM " . MyPDO::DB_FLAG . "planete WHERE gouverneur=? ORDER BY id LIMIT 1";
        $ret = MyPDO::getInstance()->query($query,$joueurId);
        if(empty($ret)){
            return null;
        }
        return $ret[0]->id;
    }

    public static function create(Systeme $Systeme, int $numero, int $slot, ?int $id_camp, ?string $bonus = null, bool $avecGouverneur = false): int{
        $nom_planete = preg_replace("/-[0-9]+/",' '.$numero,$Systeme->nom);
        $query = "INSERT INTO " . MyPDO::DB_FLAG . "planete(nom,perc_revolte,slot,gouverneur,id_systeme,id_camp,bouclier,canon,troupes,flotte_ia,nb_usine_vaiss_leger,nb_usine_vaiss_moyen,nb_usine_vaiss_lourd,nb_usine_ressource,nb_academie,nb_bouclier_planetaire,nb_canon_orbital,pnj_endurance,bonus,creato)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,now());";
        $gouverneur=0;
        $bouclier = 0;
        $canon = 0;
        $troupes = 0;
        $flotteIa = 0;
        $structures = 0;
        if($id_camp !== null){
            $structures = $slot >= self::STRUCTURES_IA_SLOT_MINIMUM ? 1 : 0;
            $bouclier = self::bouclierDeBase($slot) + $structures * self::BOUCLIER_PAR_STRUCTURE;
            $canon = self::canonDeBase($slot) + $structures * self::CANON_PAR_STRUCTURE;
            $troupes = self::troupesDeBase($slot);
            $flotteIa = self::flotteIADeBase($slot, $id_camp);
        }
        $pnj = null;
        if($avecGouverneur && random_int(1, self::PERCENT) <= self::GOUVERNEUR_POURCENT){
            $pnj = random_int(1, self::GOUVERNEUR_ETOILES_MAX);
        }
        MyPDO::getInstance()->query($query, $nom_planete,rand(0,self::INITIAL_REVOLTE_MAX),$slot,$gouverneur,$Systeme->id,$id_camp,$bouclier,$canon,$troupes,$flotteIa,0,0,0,0,0,$structures,$structures,$pnj,$bonus);
        return MyPDO::getInstance()->lastInsertId();
    }

    public function bouclierMax(): int{
        return self::bouclierDeBase($this->slot) + $this->nb_bouclier_planetaire * self::BOUCLIER_PAR_STRUCTURE;
    }

    public function canonMax(): int{
        return self::canonDeBase($this->slot) + $this->nb_canon_orbital * self::CANON_PAR_STRUCTURE;
    }

    public static function bouclierDeBase(int $slot): int{
        return $slot * self::BOUCLIER_PAR_SLOT;
    }

    public static function canonDeBase(int $slot): int{
        return $slot * self::CANON_PAR_SLOT;
    }

    public static function troupesDeBase(int $slot): int{
        return intdiv($slot, self::TROUPES_DIVISEUR_SLOT) + self::TROUPES_DE_BASE_MINIMUM;
    }

    public function refugePour(Joueur $joueur): ?Planete{
        $query = "SELECT p.id FROM " . MyPDO::DB_FLAG . "planete p JOIN " . MyPDO::DB_FLAG . "systeme s ON s.id=p.id_systeme
            WHERE p.id<>? AND (p.gouverneur=? OR (p.gouverneur=0 AND p.id_camp IS NULL))
            ORDER BY (s.x-?)*(s.x-?)+(s.y-?)*(s.y-?), p.id LIMIT " . self::REFUGES_CANDIDATS;
        $x = $this->systeme->x;
        $y = $this->systeme->y;
        foreach($this->bdd->query($query,$this->id,$joueur->id,$x,$x,$y,$y) as $ligne){
            $candidate = new Planete($ligne->id);
            if($joueur->peutCibler($candidate->systeme)){
                return $candidate;
            }
        }
        return null;
    }

    public function refugeIA(): ?Planete{
        $query = "SELECT p.id FROM " . MyPDO::DB_FLAG . "planete p JOIN " . MyPDO::DB_FLAG . "systeme s ON s.id=p.id_systeme
            WHERE p.id<>? AND p.gouverneur=0 AND p.id_camp=?
            ORDER BY (s.x-?)*(s.x-?)+(s.y-?)*(s.y-?), p.id LIMIT 1";
        $x = $this->systeme->x;
        $y = $this->systeme->y;
        $ligne = $this->bdd->query($query,$this->id,$this->id_camp,$x,$x,$y,$y)[0] ?? null;
        return $ligne === null ? null : new Planete($ligne->id);
    }

    public static function flotteIADeBase(int $slot, ?int $idCamp): int{
        if($idCamp === Camp::EMPIRE){
            return $slot * self::FLOTTE_EMPIRE_PAR_SLOT;
        }
        if($idCamp === Camp::PIRATES){
            return max(self::FLOTTE_PIRATES_MINIMUM, intdiv($slot, self::FLOTTE_PIRATES_DIVISEUR_SLOT));
        }
        return 0;
    }

    private static function expressionBouclierMax(): string{
        return "(slot*" . self::BOUCLIER_PAR_SLOT . " + nb_bouclier_planetaire*" . self::BOUCLIER_PAR_STRUCTURE . ")";
    }

    private static function expressionCanonMax(): string{
        return "(slot*" . self::CANON_PAR_SLOT . " + nb_canon_orbital*" . self::CANON_PAR_STRUCTURE . ")";
    }

    private static function expressionFlotteDeBase(): string{
        return "IF(id_camp=" . Camp::EMPIRE . ", slot * " . self::FLOTTE_EMPIRE_PAR_SLOT . ", IF(id_camp=" . Camp::PIRATES . ", GREATEST(" . self::FLOTTE_PIRATES_MINIMUM . ", slot DIV " . self::FLOTTE_PIRATES_DIVISEUR_SLOT . "), 0))";
    }

    public static function regenererDefenses(): void{
        $maintenant = Tools::now();
        $query = "UPDATE " . MyPDO::DB_FLAG . "planete SET "
            . self::expressionRegeneration('bouclier', self::expressionBouclierMax()) . ", "
            . self::expressionRegeneration('canon', self::expressionCanonMax()) . ", "
            . "flotte_ia = IF(gouverneur=0, " . self::valeurRegeneree('flotte_ia', self::expressionFlotteDeBase()) . ", flotte_ia), "
            . "troupes = IF(gouverneur=0, GREATEST(troupes, LEAST(slot DIV " . self::TROUPES_DIVISEUR_SLOT . " + " . self::TROUPES_DE_BASE_MINIMUM . ", troupes + TIMESTAMPDIFF(HOUR, defense_maj, ?) * " . self::TROUPES_PAR_HEURE . ")), troupes), "
            . "defense_maj = DATE_ADD(defense_maj, INTERVAL TIMESTAMPDIFF(HOUR, defense_maj, ?) HOUR) "
            . "WHERE defense_maj IS NOT NULL AND defense_maj <= ?";
        $db = MyPDO::getInstance();
        $db->query($query,$maintenant,$maintenant,$maintenant,$maintenant,$maintenant,Tools::formatDate(time() - self::SECONDS_PER_HOUR));
        $query = "UPDATE " . MyPDO::DB_FLAG . "planete SET defense_maj=NULL
            WHERE defense_maj IS NOT NULL
            AND (bouclier>=" . self::expressionBouclierMax() . " AND canon>=" . self::expressionCanonMax() . " AND (gouverneur<>0 OR flotte_ia>=" . self::expressionFlotteDeBase() . ") AND (gouverneur<>0 OR troupes>=slot DIV " . self::TROUPES_DIVISEUR_SLOT . " + " . self::TROUPES_DE_BASE_MINIMUM . "))";
        $db->query($query);
    }

    private static function expressionRegeneration(string $colonne, string $maximum): string{
        return $colonne . " = " . self::valeurRegeneree($colonne, $maximum);
    }

    private static function valeurRegeneree(string $colonne, string $maximum): string{
        $parHeure = "GREATEST(" . self::REGENERATION_MINIMUM_PAR_HEURE . ", " . $maximum . "*" . self::REGENERATION_POURCENT_PAR_HEURE . " DIV " . self::PERCENT . ")";
        return "GREATEST(" . $colonne . ", LEAST(" . $maximum . ", " . $colonne . " + TIMESTAMPDIFF(HOUR, defense_maj, ?) * " . $parHeure . "))";
    }

    public static function fillSysteme(Perso $Perso,Systeme $Systeme): void{
        $nb_planetes = rand(self::MIN_PLANETES, self::MAX_PLANETES);
        
        for ($i=1; $i<=$nb_planetes; $i++){
            $slot =rand(self::$min_slot,self::$max_slot);
            if($i==1){//premiere planete créer : base du joueur, le tuto la fera gouverner
                $slot = self::SLOT_DEPART; //meme base de depart pour tout les joueurs et permettre une bonne expension
            }
            $id_planete = self::create($Systeme, $i, $slot, null);
            if($i==1){
                $Planete = new Planete($id_planete);
                $Perso->goToPlanete($Planete);
            }
            
        }
    }

    public static function fillSystemeIA(Systeme $Systeme, array $id_camps, ?int $id_camp_premiere = null, bool $avecBonus = false): void{
        $nb_planetes = rand(self::MIN_PLANETES, self::MAX_PLANETES);

        for ($i=1; $i<=$nb_planetes; $i++){
            $id_camp = $id_camps[rand(0,count($id_camps)-1)];
            if($i==1 && $id_camp_premiere !== null){
                $id_camp = $id_camp_premiere;
            }
            self::create($Systeme, $i, rand(self::$min_slot,self::$max_slot), $id_camp, $avecBonus ? Bonus::tirer() : null, true);
        }
    }

    public static function fillSystemeImperial(Systeme $Systeme, bool $avecBonus = false): void{
        $nb_planetes = rand(self::MIN_PLANETES, self::MAX_PLANETES);
        $infiltres = array_merge(
            array_fill(0, rand(0, self::IMPERIAL_REBELLES_MAX), Camp::REBELLION),
            array_fill(0, rand(0, self::IMPERIAL_PIRATES_MAX), Camp::PIRATES)
        );
        $id_camps = array_pad(array_slice($infiltres, 0, $nb_planetes - 1), $nb_planetes, Camp::EMPIRE);
        shuffle($id_camps);
        foreach($id_camps as $i => $id_camp){
            self::create($Systeme, $i + 1, rand(self::$min_slot,self::$max_slot), $id_camp, $avecBonus ? Bonus::tirer() : null, true);
        }
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
                FROM " . MyPDO::DB_FLAG . "planete
                WHERE id=? ";
        $ret = $this->bdd->query($query, $id);
        if(empty($ret)){
            throw new UnexpectedValueException("Cette planète n'existe pas");
        }
        
        $this->nom = $ret[0]->nom;
        $this->id = $ret[0]->id;
        $this->perc_revolte = $ret[0]->perc_revolte;
        $this->slot = $ret[0]->slot;
        $this->gouverneur = $ret[0]->gouverneur;
        $this->id_systeme = $ret[0]->id_systeme;
        $this->nb_usine_vaiss_leger = $ret[0]->nb_usine_vaiss_leger;
        $this->nb_usine_vaiss_moyen = $ret[0]->nb_usine_vaiss_moyen;
        $this->nb_usine_vaiss_lourd = $ret[0]->nb_usine_vaiss_lourd;
        $this->nb_usine_ressource = $ret[0]->nb_usine_ressource;
        $this->nb_academie = $ret[0]->nb_academie;
        $this->revolte_maj = $ret[0]->revolte_maj;
        $this->id_camp = $ret[0]->id_camp;
        $this->bouclier = $ret[0]->bouclier;
        $this->canon = $ret[0]->canon;
        $this->troupes = $ret[0]->troupes;
        $this->bonus = $ret[0]->bonus;
        $this->defense_maj = $ret[0]->defense_maj;
        $this->flotte_ia = $ret[0]->flotte_ia;
        $this->nb_bouclier_planetaire = $ret[0]->nb_bouclier_planetaire;
        $this->nb_canon_orbital = $ret[0]->nb_canon_orbital;
        $this->pnj_endurance = $ret[0]->pnj_endurance === null ? null : (int) $ret[0]->pnj_endurance;
        $this->occupee = (bool) $ret[0]->occupee;
        $this->detournement_fin = $ret[0]->detournement_fin;
        $this->systeme = new Systeme($this->id_systeme);
        return $this;

    }
    
    public function save(): void {     
        $query ="UPDATE " . MyPDO::DB_FLAG . "planete 
            set
            nom = ?,
            perc_revolte = ?,
            slot = ?,
            gouverneur = ?,
            id_systeme = ?,
            nb_usine_vaiss_leger = ?,
            nb_usine_vaiss_moyen = ?,
            nb_usine_vaiss_lourd = ?,
            nb_usine_ressource = ?,
            nb_academie = ?,
            nb_bouclier_planetaire = ?,
            nb_canon_orbital = ?
            WHERE id =?";
        $ret = $this->bdd->query($query,          
                $this->nom,
                $this->perc_revolte,
                $this->slot,
                $this->gouverneur,
                $this->id_systeme,
                $this->nb_usine_vaiss_leger,
                $this->nb_usine_vaiss_moyen,
                $this->nb_usine_vaiss_lourd,
                $this->nb_usine_ressource,
                $this->nb_academie,
                $this->nb_bouclier_planetaire,
                $this->nb_canon_orbital,
                $this->id
                );
    }

    public function reductionTrajet(Joueur $joueur): int {
        if($this->bonus === Bonus::RELAIS && $this->gouverneur === $joueur->id){
            return Bonus::pourcent(Bonus::RELAIS);
        }
        return 0;
    }

    public function getGouverneurNom(): string {
        if($this->gouverneur === 0){
            return 'Aucun';
        }
        return (new Joueur($this->gouverneur))->getNom();
    }

    public function nombre(string $batiment): int {
        return $this->$batiment;
    }

    public function batimentsTotal(): int {
        $total = 0;
        foreach(array_keys(Batiment::CATALOGUE) as $batiment){
            $total += $this->nombre($batiment);
        }
        return $total;
    }

    public function revenuNet(): int {
        $revenu = Batiment::BASE_INCOME + $this->nombre(Batiment::FERME) * Batiment::FERME_INCOME;
        if($this->gouverneur !== 0 && (new Joueur($this->gouverneur))->enTuto()){
            return $revenu;
        }
        foreach(Batiment::CATALOGUE as $batiment => $infos){
            $revenu -= $this->nombre($batiment) * $infos['maintenance'];
        }
        return $revenu;
    }

    public function construire(Joueur $joueur, string $batiment): ?string {
        if(!isset(Batiment::CATALOGUE[$batiment])){
            return 'Bâtiment inconnu.';
        }
        if($this->gouverneur !== $joueur->id){
            return 'Vous ne gouvernez pas cette planète.';
        }
        if($joueur->revenuNet() < 0){
            return 'Votre revenu est négatif : plus de construction possible.';
        }
        $total = implode('+', array_keys(Batiment::CATALOGUE));
        $query = "UPDATE " . MyPDO::DB_FLAG . "planete SET $batiment=$batiment+1 WHERE id=? AND gouverneur=? AND ($total)<slot";
        $this->bdd->query($query,$this->id,$joueur->id);
        if($this->bdd->lastRowCount !== 1){
            return "Il n'y a plus d'emplacement libre sur cette planète.";
        }
        if(!$joueur->payer(Batiment::CATALOGUE[$batiment]['prix'])){
            $this->bdd->query("UPDATE " . MyPDO::DB_FLAG . "planete SET $batiment=$batiment-1 WHERE id=?",$this->id);
            return 'Crédits insuffisants.';
        }
        return null;
    }

    public function demolir(Joueur $joueur, string $batiment): ?string {
        if(!isset(Batiment::CATALOGUE[$batiment])){
            return 'Bâtiment inconnu.';
        }
        if($this->gouverneur !== $joueur->id){
            return 'Vous ne gouvernez pas cette planète.';
        }
        $enProduction = Vaisseau::nombreEnProduction($this->id, $batiment);
        if($enProduction > 0 && $enProduction >= $this->nombre($batiment)){
            return 'Une production est en cours dans cette usine.';
        }
        $query = "UPDATE " . MyPDO::DB_FLAG . "planete SET $batiment=$batiment-1 WHERE id=? AND gouverneur=? AND $batiment>0";
        $this->bdd->query($query,$this->id,$joueur->id);
        if($this->bdd->lastRowCount !== 1){
            return 'Aucun bâtiment de ce type à démolir.';
        }
        $joueur->crediter(Batiment::remboursement($batiment));
        return null;
    }

    public function recruter(Joueur $joueur, string $role): ?string {
        if(!isset(Perso::ROLES[$role])){
            return 'Rôle inconnu.';
        }
        if($this->gouverneur !== $joueur->id){
            return 'Vous ne gouvernez pas cette planète.';
        }
        if($this->nombre(Batiment::ACADEMIE) < 1){
            return "Cette planète n'a pas d'académie.";
        }
        if(!$joueur->payer($joueur->prixUnite(Perso::ROLES[$role]['prix']))){
            return 'Crédits insuffisants.';
        }
        Perso::recruter($joueur, $this, $role);
        return null;
    }

    public function gouverner(Joueur $joueur): bool {
        $query = "UPDATE " . MyPDO::DB_FLAG . "planete SET gouverneur=?, id_camp=?, revolte_maj=?, defense_maj=? WHERE id=? AND gouverneur=0 AND id_camp IS NULL AND pnj_endurance IS NULL";
        $this->bdd->query($query,$joueur->id,$joueur->id_camp,Tools::now(),Tools::now(),$this->id);
        return $this->bdd->lastRowCount === 1;
    }

    public function capturer(Joueur $joueur, int $troupes): bool {
        $query = "UPDATE " . MyPDO::DB_FLAG . "planete SET gouverneur=?, id_camp=?, troupes=?, bouclier=0, canon=0, perc_revolte=?, revolte_maj=?, defense_maj=?, detournement_fin=NULL, nb_usine_vaiss_leger=0, nb_usine_vaiss_moyen=0, nb_usine_vaiss_lourd=0, nb_usine_ressource=0, nb_academie=0, nb_bouclier_planetaire=0, nb_canon_orbital=0, flotte_ia=0, occupee=1 WHERE id=? AND gouverneur=? AND bouclier=0 AND canon=0 AND flotte_ia=0 AND pnj_endurance IS NULL AND nb_bouclier_planetaire=0";
        $this->bdd->query($query,$joueur->id,$joueur->id_camp,$troupes,self::CAPTURE_REVOLTE,Tools::now(),Tools::now(),$this->id,$this->gouverneur);
        if($this->bdd->lastRowCount !== 1){
            return false;
        }
        $this->bdd->query("DELETE FROM " . MyPDO::DB_FLAG . "production WHERE id_planete=?",$this->id);
        return true;
    }

    public function apaiser(int $points): void {
        $query = "UPDATE " . MyPDO::DB_FLAG . "planete SET perc_revolte=GREATEST(perc_revolte-?,0) WHERE id=?";
        $this->bdd->query($query,$points,$this->id);
    }

    public function numeroTexture(): int {
        return $this->id % self::TEXTURES + 1;
    }

    public function estOccupee(): bool {
        if($this->troupes > 0){
            return true;
        }
        $query = "SELECT COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND id_planete=? AND type=? AND arrivee IS NULL";
        return (int) $this->bdd->query($query,$this->gouverneur,$this->id,Vaisseau::TROUPE)[0]->nombre > 0;
    }

    public function redevenirNeutre(): void {
        $query = "UPDATE " . MyPDO::DB_FLAG . "planete SET gouverneur=0, id_camp=NULL, perc_revolte=?, revolte_maj=NULL, bouclier=0, canon=0, troupes=0, flotte_ia=0, occupee=0, defense_maj=?, nb_usine_vaiss_leger=0, nb_usine_vaiss_moyen=0, nb_usine_vaiss_lourd=0, nb_usine_ressource=0, nb_academie=0, nb_bouclier_planetaire=0, nb_canon_orbital=0, pnj_endurance=? WHERE id=?";
        $pnj = null;
        if(random_int(1, self::PERCENT) <= self::GOUVERNEUR_NEUTRE_POURCENT){
            $pnj = random_int(1, self::GOUVERNEUR_ETOILES_MAX);
        }
        $this->bdd->query($query,rand(0,self::INITIAL_REVOLTE_MAX),Tools::now(),$pnj,$this->id);
        $this->bdd->query("DELETE FROM " . MyPDO::DB_FLAG . "production WHERE id_planete=?",$this->id);
    }

    public function dureeProduction(Joueur $joueur, int $duree): int {
        $effective = $joueur->dureeEffective($duree);
        if($this->occupee){
            return intdiv($effective * (self::PERCENT + self::MALUS_OCCUPATION_POURCENT), self::PERCENT);
        }
        return $effective;
    }

    public function penaliteOrbite(): int {
        return min(Perso::PENALITE_ORBITE_MAX_POURCENT, array_sum(Combat::defenseurs($this)) * Perso::PENALITE_ORBITE_PAR_VAISSEAU_POURCENT);
    }

    public function updateRevolte(): void {
        if($this->gouverneur === 0){
            return;
        }
        $occupee = $this->estOccupee();
        if($this->occupee && !$occupee){
            $camp = $this->id_camp;
            $this->redevenirNeutre();
            Tools::addInfo('Une planète vous échappe', $this->nom.' n\'a plus de garnison : elle redevient neutre.', self::ILLUSTRATION_REVOLTE, $camp);
            return;
        }
        if($this->revolte_maj === null){
            $query = "UPDATE " . MyPDO::DB_FLAG . "planete SET revolte_maj=? WHERE id=? AND revolte_maj IS NULL";
            $this->bdd->query($query,Tools::now(),$this->id);
            return;
        }
        $depuis = strtotime($this->revolte_maj);
        $heures = intdiv(time() - $depuis, self::SECONDS_PER_HOUR);
        if($heures < 1){
            return;
        }
        $revolte = $this->perc_revolte;
        if(!$occupee){
            $revolte = min($revolte + $heures * self::REVOLTE_PER_HOUR, self::MAX_REVOLTE);
        }
        $nouvelleMaj = Tools::formatDate($depuis + $heures * self::SECONDS_PER_HOUR);
        $query = "UPDATE " . MyPDO::DB_FLAG . "planete SET perc_revolte=?, revolte_maj=? WHERE id=? AND revolte_maj=?";
        $this->bdd->query($query,$revolte,$nouvelleMaj,$this->id,$this->revolte_maj);
        if($this->bdd->lastRowCount !== 1 || $occupee || $revolte < self::SEUIL_INDEPENDANCE){
            return;
        }
        $camp = $this->id_camp;
        $this->redevenirNeutre();
        Tools::addInfo('Une planète se révolte', $this->nom.', sans troupes pour la tenir, a atteint '.$revolte.' % de révolte : elle devient indépendante.', self::ILLUSTRATION_REVOLTE, $camp);
    }

public function getNom(): string {
    return '<a href="?p=affichePlanete&id_planete='.$this->id.'" class="nomPlanete">'.htmlspecialchars($this->nom).'</a> '.Camp::libelle($this->id_camp);
}
    
}
