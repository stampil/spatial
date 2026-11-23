<?php

class Planete {

    const INITIAL_REVOLTE_MAX = 25;
    const MAX_REVOLTE = 100;
    const REVOLTE_PER_HOUR = 1;
    const SECONDS_PER_HOUR = 3600;
    const OPERATION_CONSTRUIRE = 'construire';
    const OPERATION_DEMOLIR = 'demolir';

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

    public static function fillSysteme(Perso $Perso,Systeme $Systeme): void{
        $nb_planetes = rand(3, 12);
        
        for ($i=1; $i<=$nb_planetes; $i++){
            $nom_planete = preg_replace("/-[0-9]+/",' '.$i,$Systeme->nom);
            
            $query = "INSERT INTO " . MyPDO::DB_FLAG . "planete(nom,perc_revolte,slot,gouverneur,id_systeme,nb_usine_vaiss_leger,nb_usine_vaiss_moyen,nb_usine_vaiss_lourd,nb_usine_ressource,nb_academie,creato)
                VALUES (?,?,?,?,?,?,?,?,?,?,now());";
            $gouverneur=0;
            $slot =rand(self::$min_slot,self::$max_slot);
            if($i==1){//premiere planete créer : base du joueur
                //$gouverneur = $Joueur->id; // = $id_joueur pas encore : tuto fera devenir gouverneur
                $slot = 8; //meme base de depart pour tout les joueurs et permettre une bonne expension
            }
            MyPDO::getInstance()->query($query, $nom_planete,rand(0,self::INITIAL_REVOLTE_MAX),$slot,$gouverneur,$Systeme->id,0,0,0,0,0);
            if($i==1){
                $id_planete =  MyPDO::getInstance()->lastInsertId();
                $Planete = new Planete($id_planete);
                $Perso->goToPlanete($Planete);
            }
            
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
            nb_academie = ?
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
                $this->id
                );
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

    public function recruter(Joueur $joueur): ?string {
        if($this->gouverneur !== $joueur->id){
            return 'Vous ne gouvernez pas cette planète.';
        }
        if($this->nombre(Batiment::ACADEMIE) < 1){
            return "Cette planète n'a pas d'académie.";
        }
        if(!$joueur->payer(Batiment::DIPLOMATE_PRIX)){
            return 'Crédits insuffisants.';
        }
        Perso::recruter($joueur, $this);
        return null;
    }

    public function gouverner(Joueur $joueur): bool {
        $query = "UPDATE " . MyPDO::DB_FLAG . "planete SET gouverneur=?, revolte_maj=? WHERE id=? AND gouverneur=0";
        $this->bdd->query($query,$joueur->id,Tools::now(),$this->id);
        return $this->bdd->lastRowCount === 1;
    }

    public function apaiser(int $points): void {
        $query = "UPDATE " . MyPDO::DB_FLAG . "planete SET perc_revolte=GREATEST(perc_revolte-?,0) WHERE id=?";
        $this->bdd->query($query,$points,$this->id);
    }

    public function updateRevolte(): void {
        if($this->gouverneur === 0){
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
        $revolte = min($this->perc_revolte + $heures * self::REVOLTE_PER_HOUR, self::MAX_REVOLTE);
        $nouvelleMaj = Tools::formatDate($depuis + $heures * self::SECONDS_PER_HOUR);
        $query = "UPDATE " . MyPDO::DB_FLAG . "planete SET perc_revolte=?, revolte_maj=? WHERE id=? AND revolte_maj=?";
        $this->bdd->query($query,$revolte,$nouvelleMaj,$this->id,$this->revolte_maj);
        if($this->bdd->lastRowCount !== 1 || $revolte < self::MAX_REVOLTE){
            return;
        }
        $query = "UPDATE " . MyPDO::DB_FLAG . "planete SET gouverneur=0, perc_revolte=?, revolte_maj=NULL, nb_usine_vaiss_leger=0, nb_usine_vaiss_moyen=0, nb_usine_vaiss_lourd=0, nb_usine_ressource=0, nb_academie=0 WHERE id=?";
        $this->bdd->query($query,rand(0,self::INITIAL_REVOLTE_MAX),$this->id);
        $this->bdd->query("DELETE FROM " . MyPDO::DB_FLAG . "production WHERE id_planete=?",$this->id);
        Tools::addMessage($this->nom.' est devenue indépendante : sa révolte a atteint '.self::MAX_REVOLTE.' %.');
    }

public function getNom(): string {
    return '<a target="_blank" href="?p=affichePlanete&id_planete='.$this->id.'" class="nomPlanete">'.htmlspecialchars($this->nom).'</a>';
}
    
}
