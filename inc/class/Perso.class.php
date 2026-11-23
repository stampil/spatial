<?php

class Perso {

    const MAX_STARS = 5;
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
    const DIPLOMATE_NOM = 'Diplomate';

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

    

    

    
    public static function create(Joueur $Joueur): int {
        $query = "INSERT INTO " . MyPDO::DB_FLAG . "perso (id_joueur,nom,PV,FO,diplomatie,sur_planete,creato) VALUES(?,?,?,?,?,?,now()) ";
        MyPDO::getInstance()->query($query,$Joueur->id,$Joueur->nom,rand(1,5),rand(1,5),rand(1,5),0);

        $id_perso =  MyPDO::getInstance()->lastInsertId();
        
        return $id_perso;
    }
    
   
    

        
    public static function recruter(Joueur $Joueur, Planete $Planete): int {
        $nom = self::DIPLOMATE_NOM.' '.(count(self::liste($Joueur)) + 1);
        $query = "INSERT INTO " . MyPDO::DB_FLAG . "perso (id_joueur,nom,PV,FO,diplomatie,sur_planete,creato) VALUES(?,?,?,?,?,?,now()) ";
        MyPDO::getInstance()->query($query,$Joueur->id,$nom,rand(1,self::MAX_STARS),rand(1,self::MAX_STARS),rand(1,self::MAX_STARS),$Planete->id);
        return MyPDO::getInstance()->lastInsertId();
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
        $this->creato = $ret[0]->creato;
        
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
    public function isImprisoned(): bool {
        return !Tools::isFinCompteRebours($this->fin_prison);
    }

    public function canAct(): bool {
        return $this->fin_mort === null && !$this->isImprisoned() && $this->action_diplomatie === null;
    }

    public function dureeDiplomatie(): int {
        return intdiv(self::DIPLOMATIE_SECONDS, $this->diplomatie);
    }

    public function chanceDeSucces(Joueur $joueur): int {
        if($joueur->etape_tuto < Joueur::TUTO_END_STEP){
            return self::MAX_PERCENT;
        }
        return self::SUCCESS_BASE_PERCENT + self::SUCCESS_PER_DIPLOMATIE_PERCENT * $this->diplomatie;
    }

    public function startDiplomatie(int $action): ?string {
        if($action !== self::ACTION_CONQUETE && $action !== self::ACTION_APAISER){
            return 'Action de diplomatie inconnue.';
        }
        if($this->fin_mort !== null){
            return 'Ce perso est mort.';
        }
        if($this->isImprisoned()){
            return 'Ce perso est emprisonné.';
        }
        if($action === self::ACTION_CONQUETE && $this->sur_planete->gouverneur !== 0){
            return 'Cette planète a déjà un gouverneur.';
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
        elseif($this->action_diplomatie !== null && Tools::isFinCompteRebours($this->fin_diplomatie)){
            $this->finishDiplomatie($joueur);
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
            $planete->apaiser($this->diplomatie * self::APAISER_PER_DIPLOMATIE);
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
        Tools::addMessage('Vous gouvernez maintenant '.$planete->nom.'.');
        if($joueur->etape_tuto < Joueur::TUTO_END_STEP){
            $joueur->etape_tuto = Joueur::TUTO_END_STEP;
            $joueur->save();
            Tools::addMessage('Le tutoriel est terminé.');
        }
    }

    private function fail(): void {
        if(random_int(1, self::MAX_PERCENT) <= self::PRISON_SHARE_PERCENT){
            $query = "UPDATE " . MyPDO::DB_FLAG . "perso SET fin_prison=? WHERE id=?";
            $this->bdd->query($query,Tools::nowPlus(self::PRISON_SECONDS),$this->id);
            Tools::addMessage($this->nom.' a échoué et a été emprisonné pendant '.intdiv(self::PRISON_SECONDS, self::SECONDS_PER_MINUTE).' minutes.');
            return;
        }
        $query = "UPDATE " . MyPDO::DB_FLAG . "perso SET fin_mort=? WHERE id=?";
        $this->bdd->query($query,Tools::nowPlus(self::RESPAWN_SECONDS),$this->id);
        Tools::addMessage($this->nom.' a échoué et a été tué. Un nouveau perso arrivera dans '.intdiv(self::RESPAWN_SECONDS, self::SECONDS_PER_MINUTE).' minutes.');
    }

    private function respawn(): void {
        $planeteId = Planete::premiereGouvernee($this->joueur->id) ?? $this->sur_planete->id;
        $query = "UPDATE " . MyPDO::DB_FLAG . "perso SET fin_mort=NULL, fin_prison=NULL, PV=?, FO=?, diplomatie=?, sur_planete=? WHERE id=? AND fin_mort IS NOT NULL";
        $this->bdd->query($query,rand(1,self::MAX_STARS),rand(1,self::MAX_STARS),rand(1,self::MAX_STARS),$planeteId,$this->id);
        if($this->bdd->lastRowCount === 1){
            Tools::addMessage('Un nouveau perso, '.$this->nom.', est arrivé.');
        }
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
