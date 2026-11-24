<?php

class Tuto {

    const ETAPE_USINE = 2;
    const ETAPE_RECONNAISSANCE = 3;
    const ETAPE_EXPLORATION = 4;
    const ETAPE_PREPARATION = 5;
    const ETAPE_ENVOI = 6;
    const ETAPE_ASSAUT = 7;
    const PRIME_CREDITS = 800;
    const CHASSEURS_REQUIS = 3;
    const TROUPES_REQUISES = 3;
    const BARGES_REQUISES = 1;

    public static function avancer(Joueur $joueur): void {
        if($joueur->etape_tuto >= self::ETAPE_USINE && $joueur->enTuto() && $joueur->id_systeme_voisin === null){
            $idPlanete = Planete::premiereGouvernee($joueur->id);
            Univers::creerVoisin($joueur, $idPlanete === null ? null : (new Planete($idPlanete))->systeme);
        }
        while($joueur->etape_tuto >= self::ETAPE_USINE && $joueur->enTuto() && self::objectifAtteint($joueur)){
            $query = "UPDATE " . MyPDO::DB_FLAG . "joueur SET etape_tuto=etape_tuto+1 WHERE id=? AND etape_tuto=?";
            MyPDO::getInstance()->query($query,$joueur->id,$joueur->etape_tuto);
            if(MyPDO::getInstance()->lastRowCount === 1){
                self::entrerDansEtape($joueur, $joueur->etape_tuto + 1);
            }
            $joueur->load($joueur->id);
        }
    }

    private static function entrerDansEtape(Joueur $joueur, int $etape): void {
        if($etape === self::ETAPE_PREPARATION){
            $joueur->crediter(self::PRIME_CREDITS);
            Tools::addMessage('Tutoriel : étape réussie, prime de '.self::PRIME_CREDITS.' crédits pour préparer l\'assaut.');
        }
        elseif($etape === Joueur::TUTO_END_STEP){
            MyPDO::getInstance()->query("UPDATE " . MyPDO::DB_FLAG . "joueur SET fin_tuto=? WHERE id=?",Tools::now(),$joueur->id);
            Tools::addMessage('Tutoriel terminé : vous avez capturé une planète des Pirates.');
        }
        else{
            Tools::addMessage('Tutoriel : étape réussie.');
        }
    }

    private static function objectifAtteint(Joueur $joueur): bool {
        $db = MyPDO::getInstance();
        switch($joueur->etape_tuto){
            case self::ETAPE_USINE:
                $query = "SELECT COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "planete WHERE gouverneur=? AND " . Batiment::USINE_LEGERE . ">=1";
                return $db->query($query,$joueur->id)[0]->nombre > 0;
            case self::ETAPE_RECONNAISSANCE:
                $query = "SELECT COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND type=?";
                return $db->query($query,$joueur->id,Vaisseau::RECONNAISSANCE)[0]->nombre > 0;
            case self::ETAPE_EXPLORATION:
                return Decouverte::existe($joueur->id, $joueur->id_systeme_voisin);
            case self::ETAPE_PREPARATION:
                $query = "SELECT type, COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? GROUP BY type";
                return self::atteint($db->query($query,$joueur->id), self::requis());
            case self::ETAPE_ENVOI:
                $query = "SELECT type, COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND id_planete=? AND arrivee IS NULL GROUP BY type";
                return self::atteint($db->query($query,$joueur->id,self::planeteCible($joueur)->id), array('chasseur' => 1, Vaisseau::TROUPE => 1));
            case self::ETAPE_ASSAUT:
                return self::planeteCible($joueur)->gouverneur === $joueur->id;
        }
        return false;
    }

    private static function requis(): array {
        return array('chasseur' => self::CHASSEURS_REQUIS, Vaisseau::TROUPE => self::TROUPES_REQUISES, Vaisseau::BARGE => self::BARGES_REQUISES);
    }

    private static function atteint(array $lignes, array $requis): bool {
        $possedes = array();
        foreach($lignes as $ligne){
            $possedes[$ligne->type] = (int) $ligne->nombre;
        }
        foreach($requis as $type => $minimum){
            if(($possedes[$type] ?? 0) < $minimum){
                return false;
            }
        }
        return true;
    }

    private static function planeteCible(Joueur $joueur): Planete {
        $query = "SELECT id FROM " . MyPDO::DB_FLAG . "planete WHERE id_systeme=? ORDER BY id LIMIT 1";
        return new Planete(MyPDO::getInstance()->query($query,$joueur->id_systeme_voisin)[0]->id);
    }

    public static function titre(Joueur $joueur): string {
        switch($joueur->etape_tuto){
            case self::ETAPE_USINE:
                return 'Construire une usine';
            case self::ETAPE_RECONNAISSANCE:
                return 'Produire un vaisseau de reconnaissance';
            case self::ETAPE_EXPLORATION:
                return 'Explorer le système voisin';
            case self::ETAPE_PREPARATION:
                return 'Préparer l\'assaut';
            case self::ETAPE_ENVOI:
                return 'Envoyer la flotte';
            case self::ETAPE_ASSAUT:
                return 'Prendre la planète pirate';
        }
        return '';
    }

    public static function texte(Joueur $joueur): string {
        $idPlanete = Planete::premiereGouvernee($joueur->id);
        if($idPlanete === null){
            return 'Vous n\'avez plus de planète : conquérez-en une par la diplomatie pour poursuivre le tutoriel.';
        }
        $planete = new Planete($idPlanete);
        switch($joueur->etape_tuto){
            case self::ETAPE_USINE:
                return 'Votre planète '.$planete->getNom().' peut accueillir des bâtiments. Ouvrez sa fiche et construisez une usine de vaisseaux légers.';
            case self::ETAPE_RECONNAISSANCE:
                return 'Sur la fiche de '.$planete->getNom().', l\'usine de vaisseaux légers peut produire un vaisseau de reconnaissance. Lancez sa production : pendant le tutoriel, les durées sont divisées par '.Joueur::TUTO_DIVISEUR_DUREES.'.';
            case self::ETAPE_EXPLORATION:
                return 'Un système voisin, encore inconnu, existe près du vôtre : sur la <a href="?p=carte">carte</a>, c\'est le cercle en pointillés. Touchez votre système, puis « Envoyer » pour votre vaisseau de reconnaissance, touchez le secteur inconnu et « Envoyer ici » : à son arrivée, le système sera exploré.';
            case self::ETAPE_PREPARATION:
                return 'Le système voisin est exploré : sa planète '.self::planeteCible($joueur)->getNom().' est aux mains des Pirates. Préparez l\'assaut sur la fiche de '.$planete->getNom().' : construisez une usine de vaisseaux moyens et une académie, puis produisez '.self::BARGES_REQUISES.' barge de débarquement, '.self::TROUPES_REQUISES.' troupes d\'assaut et '.self::CHASSEURS_REQUIS.' chasseurs.';
            case self::ETAPE_ENVOI:
                return 'Sur la <a href="?p=carte">carte</a>, touchez votre système, gardez vos chasseurs, la barge et les troupes puis « Envoyer ». Touchez ensuite le système voisin et « Envoyer ici » sur '.self::planeteCible($joueur)->getNom().' (une barge transporte '.Vaisseau::CAPACITE_BARGE.' troupes).';
            case self::ETAPE_ASSAUT:
                return 'Votre flotte est arrivée. Ouvrez la fiche de '.self::planeteCible($joueur)->getNom().' : attaquez avec vos vaisseaux pour détruire sa défense, puis débarquez vos troupes. Si l\'assaut échoue, produisez de nouvelles troupes et recommencez.';
        }
        return '';
    }

}
