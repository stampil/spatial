<?php

class Tuto {

    const ETAPE_USINE = 2;
    const ETAPE_RECONNAISSANCE = 3;
    const ETAPE_EXPLORATION = 4;
    const ETAPE_PREPARATION = 5;
    const ETAPE_FLOTTE = 6;
    const ETAPE_ENVOI = 7;
    const ETAPE_ASSAUT = 8;
    const ETAPE_ANALYSE = 9;
    const ETAPE_ORBITE = 10;
    const ETAPE_ASSASSIN = 11;
    const ETAPE_SABOTAGE = 12;
    const ETAPE_HEROS = 13;
    const ETAPE_BONUS = 14;
    const ETAPE_DEMOLITION = 15;
    const ETAPE_MENACE = 16;
    const ETAPE_RESTRICTOR = 17;
    const ETAPE_CONQUETE = 18;
    const ETAPE_NOMMER = 19;
    const ETAPE_FERMES = 20;
    const ETAPES_A_VALIDER = array(self::ETAPE_BONUS, self::ETAPE_DEMOLITION);
    const PRIME_HEROS = 350;
    const PRIME_CREDITS = 800;
    const PRIME_ASSASSIN = 400;
    const PRIME_RESTRICTOR = 1000;
    const PRIME_MARGE_POURCENT = 20;
    const CLE_RENOMMER = 'renommer-planete';
    const CLE_RENOMMER_VALIDER = 'renommer-planete-valider';
    const FLOTTE_REDUITE = 2;
    const GOUVERNEUR_ETOILES = 1;
    const ORBITE_CHASSEURS = 1;
    const CHASSEURS_REQUIS = 3;
    const TROUPES_REQUISES = 3;
    const FREGATES_REQUISES = 1;
    const BARGES_REQUISES = 1;
    const CLE_CONSTRUIRE = 'construire-';
    const CLE_PRODUIRE = 'produire-';
    const CLE_RECRUTER = 'recruter-';
    const CLE_VOYAGER = 'voyager-';
    const CLE_PLANETE = 'planete-';
    const CLE_FICHE = 'planete-fiche';
    const CLE_VOYAGER_ICI = 'voyager-ici';
    const CLE_VOYAGER_FLOTTE = 'voyager-flotte';
    const CLE_PERSO = 'perso-';
    const CLE_COMPRENDRE = 'comprendre';
    const CLE_EN_ROUTE = 'en-route';

    public static function avancer(Joueur $joueur): void {
        if($joueur->etape_tuto >= self::ETAPE_USINE && $joueur->enTuto() && $joueur->id_systeme_voisin === null){
            $idPlanete = Planete::premiereGouvernee($joueur->id);
            Univers::creerVoisin($joueur, $idPlanete === null ? null : (new Planete($idPlanete))->systeme);
        }
        if($joueur->etape_tuto === self::ETAPE_RESTRICTOR && $joueur->enTuto()){
            self::garnirPlaneteDeux($joueur);
        }
        if($joueur->etape_tuto === self::ETAPE_CONQUETE && $joueur->enTuto()){
            self::envoyerRenfort($joueur);
        }
        while($joueur->etape_tuto >= self::ETAPE_USINE && $joueur->enTuto() && self::objectifAtteint($joueur)){
            self::passerALEtapeSuivante($joueur);
        }
    }

    public static function verifierDestination(Joueur $joueur, Planete $destination): ?string {
        if(!$joueur->enTuto() || $joueur->id_systeme_voisin === null || $destination->id_systeme !== $joueur->id_systeme_voisin){
            return null;
        }
        if($destination->id === self::planeteCible($joueur)->id){
            return null;
        }
        $deux = self::planeteDeux($joueur);
        if($joueur->etape_tuto >= self::ETAPE_ANALYSE && $deux !== null && $destination->id === $deux->id){
            return null;
        }
        $trois = self::planeteTrois($joueur);
        if($joueur->etape_tuto >= self::ETAPE_RESTRICTOR && $trois !== null && $destination->id === $trois->id){
            return null;
        }
        return 'Ce secteur est guidé par le tutoriel : terminez-le avant d\'explorer ses autres planètes.';
    }

    public static function valider(Joueur $joueur): void {
        if($joueur->enTuto() && in_array($joueur->etape_tuto, self::ETAPES_A_VALIDER, true)){
            self::passerALEtapeSuivante($joueur);
            self::avancer($joueur);
        }
    }

    private static function passerALEtapeSuivante(Joueur $joueur): void {
        $query = "UPDATE " . MyPDO::DB_FLAG . "joueur SET etape_tuto=etape_tuto+1 WHERE id=? AND etape_tuto=?";
        MyPDO::getInstance()->query($query,$joueur->id,$joueur->etape_tuto);
        if(MyPDO::getInstance()->lastRowCount === 1){
            self::entrerDansEtape($joueur, $joueur->etape_tuto + 1);
        }
        $joueur->load($joueur->id);
    }

    public static function attributsFleche(Joueur $joueur, string $page): string {
        if(!$joueur->enTuto()){
            return '';
        }
        $route = self::route($joueur, $page);
        if(count($route) === 0){
            return '';
        }
        $attributs = isset(MENU_PLAYER[$route[0]['page']]) ? ' data-tuto-menu="'.$route[0]['page'].'"' : '';
        foreach(array_reverse($route) as $niveau){
            if($niveau['page'] === $page){
                return $attributs.($niveau['cle'] === null ? '' : ' data-tuto-cible="'.$niveau['cle'].'"');
            }
        }
        return $attributs;
    }

    private static function niveau(string $page, ?string $cle): array {
        return array('page' => $page, 'cle' => $cle);
    }

    private static function routeVersPlanete(Planete $mere, string $cle): array {
        return array(self::niveau('listePlanete', self::CLE_PLANETE.$mere->id), self::niveau('affichePlanete', $cle));
    }

    private static function explorationEnCours(Joueur $joueur): bool {
        $query = "SELECT COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND exploration=1 AND arrivee IS NOT NULL";
        return (int) MyPDO::getInstance()->query($query,$joueur->id)[0]->nombre > 0;
    }

    public static function transitAExpliquer(Joueur $joueur): bool {
        return $joueur->enTuto() && $joueur->etape_tuto === self::ETAPE_EXPLORATION && !$joueur->tuto_transit_lu && self::explorationEnCours($joueur);
    }

    private static function routeDeProduction(Planete $mere, string $type): array {
        if(self::nombreEnProduction($mere, $type) > 0){
            return array();
        }
        return self::routeVersPlanete($mere, self::CLE_PRODUIRE.$type);
    }

    private static function nombreEnProduction(Planete $planete, string $type): int {
        $nombre = 0;
        foreach(Vaisseau::enProduction($planete->id) as $ligne){
            if($ligne->type === $type){
                $nombre++;
            }
        }
        return $nombre;
    }

    private static function enProductionQuelconque(Planete $planete): bool {
        return count(Vaisseau::enProduction($planete->id)) > 0;
    }

    private static function nombreDeVaisseaux(Joueur $joueur, string $type, bool $embarques): int {
        $query = "SELECT COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND type=? AND id_hote IS " . ($embarques ? 'NOT ' : '') . "NULL";
        return (int) MyPDO::getInstance()->query($query,$joueur->id,$type)[0]->nombre;
    }

    private static function route(Joueur $joueur, string $page): array {
        if($joueur->etape_tuto === 0){
            return array(self::niveau('listePerso', null));
        }
        if($joueur->etape_tuto === 1){
            return array(self::niveau('listePerso', 'perso'), self::niveau('affichePerso', 'negocier'));
        }
        $idPlanete = Planete::premiereGouvernee($joueur->id);
        if($idPlanete === null){
            return array();
        }
        $mere = new Planete($idPlanete);
        switch($joueur->etape_tuto){
            case self::ETAPE_USINE:
                return self::routeVersPlanete($mere, self::CLE_CONSTRUIRE.Batiment::USINE_LEGERE);
            case self::ETAPE_RECONNAISSANCE:
                return self::routeDeProduction($mere, Vaisseau::RECONNAISSANCE);
            case self::ETAPE_EXPLORATION:
                if(self::explorationEnCours($joueur)){
                    return $joueur->tuto_transit_lu ? array() : array(self::niveau($page, self::CLE_EN_ROUTE));
                }
                return array(self::niveau('listeVaisseau', 'chercher'));
            case self::ETAPE_PREPARATION:
                $cle = self::cleDePreparation($joueur, $mere);
                return $cle === null ? array() : self::routeVersPlanete($mere, $cle);
            case self::ETAPE_FLOTTE:
                return array(self::niveau('listeVaisseau', 'creer-flotte enregistrer-flotte'));
            case self::ETAPE_ENVOI:
                $cle = self::cleDEnvoi($joueur);
                return self::routeVersLaCarte(array(self::niveau('listeVaisseau', $cle)), $cle === self::CLE_VOYAGER_FLOTTE ? self::planeteCible($joueur) : null);
            case self::ETAPE_ASSAUT:
                $cible = self::planeteCible($joueur);
                return array(self::niveau('carte', self::CLE_FICHE.' systeme-'.$cible->id_systeme.' '.self::CLE_PLANETE.$cible->id), self::niveau('affichePlanete', 'debarquement'));
            case self::ETAPE_ANALYSE:
                return self::routeVersLaCarte(array(self::niveau('listeVaisseau', self::CLE_VOYAGER.Vaisseau::RECONNAISSANCE)), self::planeteDeux($joueur));
            case self::ETAPE_ORBITE:
                $chasseursEmbarques = self::nombreDeVaisseaux($joueur, Vaisseau::CHASSEUR, true) > 0;
                return self::routeVersLaCarte(array(self::niveau('listeVaisseau', $chasseursEmbarques ? 'debarquer-valider decharger' : self::CLE_VOYAGER.Vaisseau::CHASSEUR)), $chasseursEmbarques ? null : self::planeteCible($joueur));
            case self::ETAPE_ASSASSIN:
                return self::routeDAssassin($joueur, $mere);
            case self::ETAPE_SABOTAGE:
                return array(self::niveau('listePerso', self::CLE_PERSO.Perso::ROLE_ASSASSIN), self::niveau('affichePerso', 'saboter'));
            case self::ETAPE_HEROS:
                return self::routeVersPlanete($mere, self::CLE_RECRUTER.Perso::ROLE_HEROS);
            case self::ETAPE_MENACE:
                return array(self::niveau('listeVaisseau', 'combattre laisser'));
            case self::ETAPE_RESTRICTOR:
                return self::routeDeRestrictor($joueur, $mere);
            case self::ETAPE_CONQUETE:
                return self::routeDeConquete($joueur, $mere);
            case self::ETAPE_NOMMER:
                $renommable = self::planeteRenommable($joueur, $mere);
                return $renommable === null ? array() : self::routeVersPlanete($renommable, self::CLE_RENOMMER_VALIDER.' '.self::CLE_RENOMMER);
            case self::ETAPE_FERMES:
                $aGarnir = self::planeteSansFermes($joueur);
                return $aGarnir === null ? array() : self::routeVersPlanete($aGarnir, self::CLE_CONSTRUIRE.Batiment::FERME);
        }
        return array(self::niveau($page, self::CLE_COMPRENDRE));
    }

    private static function routeVersLaCarte(array $route, ?Planete $cible): array {
        if($cible !== null){
            array_push($route, self::niveau('carte', self::CLE_VOYAGER_ICI.' '.self::CLE_PLANETE.$cible->id.' systeme-'.$cible->id_systeme));
        }
        return $route;
    }

    private static function cleDePreparation(Joueur $joueur, Planete $mere): ?string {
        if($mere->nombre(Batiment::USINE_MOYENNE) < 1){
            return self::CLE_CONSTRUIRE.Batiment::USINE_MOYENNE;
        }
        if($mere->nombre(Batiment::ACADEMIE) < 1){
            return self::CLE_CONSTRUIRE.Batiment::ACADEMIE;
        }
        $stock = Vaisseau::stock($mere->id, $joueur->id);
        foreach(self::requis() as $type => $minimum){
            if(($stock[$type] ?? 0) + self::nombreEnProduction($mere, $type) < $minimum){
                return self::CLE_PRODUIRE.$type;
            }
        }
        return self::enProductionQuelconque($mere) ? null : self::CLE_PRODUIRE.Vaisseau::TROUPE;
    }

    private static function routeDAssassin(Joueur $joueur, Planete $mere): array {
        if(!self::possedePerso($joueur, Perso::ROLE_ASSASSIN)){
            return self::routeVersPlanete($mere, self::CLE_RECRUTER.Perso::ROLE_ASSASSIN);
        }
        if(self::nombreDeVaisseaux($joueur, Vaisseau::TRANSPORT_PERSO, false) < 1){
            return self::routeDeProduction($mere, Vaisseau::TRANSPORT_PERSO);
        }
        $assassin = MyPDO::getInstance()->query("SELECT embarque, arrivee, sur_planete FROM " . MyPDO::DB_FLAG . "perso WHERE id_joueur=? AND role=? AND fin_mort IS NULL ORDER BY id LIMIT 1",$joueur->id,Perso::ROLE_ASSASSIN)[0];
        if((int) $assassin->embarque === 1){
            return self::routeVersLaCarte(array(self::niveau('listeVaisseau', self::CLE_VOYAGER.Vaisseau::TRANSPORT_PERSO)), self::planeteDeux($joueur));
        }
        $versLaFiche = array(self::niveau('listePerso', self::CLE_PERSO.Perso::ROLE_ASSASSIN));
        if($assassin->arrivee !== null){
            return $versLaFiche;
        }
        $deux = self::planeteDeux($joueur);
        $surPlaceDeux = $deux !== null && (int) $assassin->sur_planete === $deux->id;
        return array_merge($versLaFiche, array(self::niveau('affichePerso', $surPlaceDeux ? 'assassin' : 'embarquer')));
    }

    private static function cleDEnvoi(Joueur $joueur): string {
        if(self::nombreDeVaisseaux($joueur, Vaisseau::TROUPE, true) < self::TROUPES_REQUISES){
            return 'embarquer-valider-'.Vaisseau::BARGE.' embarquer-'.Vaisseau::BARGE;
        }
        if(self::nombreDeVaisseaux($joueur, Vaisseau::CHASSEUR, true) < self::CHASSEURS_REQUIS){
            return 'embarquer-valider-fregate embarquer-fregate';
        }
        return self::CLE_VOYAGER_FLOTTE;
    }

    private static function routeDeRestrictor(Joueur $joueur, Planete $mere): array {
        if($mere->nombre(Batiment::USINE_LOURDE) < 1){
            return self::routeVersPlanete($mere, self::CLE_CONSTRUIRE.Batiment::USINE_LOURDE);
        }
        if(self::nombreDeVaisseaux($joueur, Vaisseau::RESTRICTOR, false) < 1){
            return self::routeDeProduction($mere, Vaisseau::RESTRICTOR);
        }
        $deux = self::planeteDeux($joueur);
        if(!self::restrictorSur($joueur, $deux)){
            return self::routeVersLaCarte(array(self::niveau('listeVaisseau', self::CLE_VOYAGER.Vaisseau::RESTRICTOR)), $deux);
        }
        if(self::chasseursSurRestrictor($joueur) < Vaisseau::CATALOGUE[Vaisseau::RESTRICTOR][Vaisseau::CAPACITE_CHASSEURS]){
            return array(self::niveau('listeVaisseau', 'embarquer-valider-'.Vaisseau::RESTRICTOR.' embarquer-'.Vaisseau::RESTRICTOR));
        }
        return self::routeVersLaCarte(array(self::niveau('listeVaisseau', self::CLE_VOYAGER.Vaisseau::RESTRICTOR)), self::planeteTrois($joueur));
    }

    private static function phraseRevenuParMinute(Joueur $joueur): string {
        $revenu = $joueur->revenuNet();
        $parMinute = intdiv($revenu, Joueur::MINUTES_PAR_HEURE);
        $gain = $parMinute > 0 ? 'environ '.$parMinute.' '.__('credit', 'credits', $parMinute).' chaque minute' : 'une fraction de crédit chaque minute, qui s\'accumule jusqu\'à faire un crédit entier';
        return 'Le revenu affiché en haut de l\'écran est un montant par heure ('.sprintf('%+d', $revenu).' cr/h), mais vous en gagnez l\'équivalent chaque minute : '.$gain.'.';
    }

    private static function primeDe(int $cout): int {
        return intdiv($cout * (Planete::PERCENT + self::PRIME_MARGE_POURCENT) + Planete::PERCENT - 1, Planete::PERCENT);
    }

    private static function planetesDuSecteur(Joueur $joueur): array {
        $planetes = array();
        foreach(array(self::planeteCible($joueur), self::planeteDeux($joueur), self::planeteTrois($joueur)) as $planete){
            if($planete !== null){
                $planetes[$planete->id] = $planete;
            }
        }
        return array_values($planetes);
    }

    private static function vider(Joueur $joueur, array $planetes): void {
        foreach($planetes as $planete){
            $query = "UPDATE " . MyPDO::DB_FLAG . "planete SET id_camp=?, gouverneur=0, pnj_endurance=NULL, flotte_ia=0, nb_bouclier_planetaire=0, nb_canon_orbital=0, bouclier=0, canon=0, troupes=0, defense_maj=? WHERE id=?";
            MyPDO::getInstance()->query($query,Camp::PIRATES,Tools::now(),$planete->id);
        }
    }

    private static function bargeSur(Joueur $joueur, Planete $planete): ?object {
        $query = "SELECT id FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND type=? AND id_planete=? AND arrivee IS NULL AND id_flotte IS NULL ORDER BY id LIMIT 1";
        return MyPDO::getInstance()->query($query,$joueur->id,Vaisseau::BARGE,$planete->id)[0] ?? null;
    }

    private static function routeDeConquete(Joueur $joueur, Planete $mere): array {
        $deux = self::planeteDeux($joueur);
        $trois = self::planeteTrois($joueur);
        $cible = $deux->gouverneur === $joueur->id ? $trois : $deux;
        $depart = $cible->id === $deux->id ? $mere : $deux;
        if(self::bargeSur($joueur, $cible) !== null){
            return array(self::niveau('carte', self::CLE_FICHE.' systeme-'.$cible->id_systeme.' '.self::CLE_PLANETE.$cible->id), self::niveau('affichePlanete', 'debarquement'));
        }
        $barge = self::bargeSur($joueur, $depart);
        if($barge === null){
            return self::routeDeProduction($mere, Vaisseau::BARGE);
        }
        if(Vaisseau::troupesEnBarge($depart->id, $joueur->id) < 1){
            if((Vaisseau::stockLibre($depart->id, $joueur->id)[Vaisseau::TROUPE] ?? 0) > 0){
                return array(self::niveau('listeVaisseau', 'embarquer-valider-'.Vaisseau::BARGE.' embarquer-'.Vaisseau::BARGE));
            }
            return $depart->id === $mere->id ? self::routeDeProduction($mere, Vaisseau::TROUPE) : array();
        }
        return self::routeVersLaCarte(array(self::niveau('listeVaisseau', self::CLE_VOYAGER.Vaisseau::BARGE)), $cible);
    }

    private static function envoyerRenfort(Joueur $joueur): void {
        $deux = self::planeteDeux($joueur);
        if($deux->gouverneur !== $joueur->id || self::planeteTrois($joueur)->gouverneur === $joueur->id || self::bargeSur($joueur, $deux) === null){
            return;
        }
        if(Vaisseau::troupesEnBarge($deux->id, $joueur->id) + (Vaisseau::stockLibre($deux->id, $joueur->id)[Vaisseau::TROUPE] ?? 0) > 0){
            return;
        }
        MyPDO::getInstance()->query("INSERT INTO " . MyPDO::DB_FLAG . "vaisseau (id_joueur,id_planete,type,creato) VALUES (?,?,?,?)",$joueur->id,$deux->id,Vaisseau::TROUPE,Tools::now());
        Tools::addMessage('Tutoriel : un renfort de troupes vous attend sur '.$deux->getNom().'.');
    }

    private static function planeteRenommable(Joueur $joueur, Planete $mere): ?Planete {
        foreach(array_merge(array(self::planeteDeux($joueur), self::planeteTrois($joueur), self::planeteCible($joueur)), array($mere)) as $planete){
            if($planete !== null && $planete->peutEtreRenommee($joueur)){
                return $planete;
            }
        }
        return null;
    }

    private static function fermesManquantes(Joueur $joueur): int {
        $manquantes = 0;
        foreach(self::planetesDuSecteur($joueur) as $planete){
            $manquantes += $planete->slot - $planete->nombre(Batiment::FERME);
        }
        return $manquantes;
    }

    private static function planeteSansFermes(Joueur $joueur): ?Planete {
        foreach(self::planetesDuSecteur($joueur) as $planete){
            if($planete->gouverneur !== $joueur->id || $planete->nombre(Batiment::FERME) < $planete->slot){
                return $planete;
            }
        }
        return null;
    }

    private static function remplacerParDesFermes(Joueur $joueur): void {
        foreach(self::planetesDuSecteur($joueur) as $planete){
            foreach(array_keys(Batiment::CATALOGUE) as $batiment){
                if($batiment === Batiment::FERME || $planete->nombre($batiment) < 1){
                    continue;
                }
                $joueur->crediter($planete->nombre($batiment) * Batiment::remboursement($batiment));
                MyPDO::getInstance()->query("UPDATE " . MyPDO::DB_FLAG . "planete SET $batiment=0 WHERE id=?",$planete->id);
            }
        }
    }

    private static function restrictorSur(Joueur $joueur, ?Planete $planete): bool {
        if($planete === null){
            return false;
        }
        $query = "SELECT COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND type=? AND id_planete=? AND arrivee IS NULL";
        return (int) MyPDO::getInstance()->query($query,$joueur->id,Vaisseau::RESTRICTOR,$planete->id)[0]->nombre > 0;
    }

    private static function chasseursSurRestrictor(Joueur $joueur): int {
        $query = "SELECT COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau chasseur JOIN " . MyPDO::DB_FLAG . "vaisseau hote ON hote.id=chasseur.id_hote WHERE chasseur.id_joueur=? AND chasseur.type=? AND hote.type=?";
        return (int) MyPDO::getInstance()->query($query,$joueur->id,Vaisseau::CHASSEUR,Vaisseau::RESTRICTOR)[0]->nombre;
    }

    private static function garnirPlaneteDeux(Joueur $joueur): void {
        $deux = self::planeteDeux($joueur);
        if(!self::restrictorSur($joueur, $deux)){
            return;
        }
        $presents = (Vaisseau::stockLibre($deux->id, $joueur->id)[Vaisseau::CHASSEUR] ?? 0) + self::chasseursSurRestrictor($joueur);
        for($i = $presents; $i < Vaisseau::CATALOGUE[Vaisseau::RESTRICTOR][Vaisseau::CAPACITE_CHASSEURS]; $i++){
            MyPDO::getInstance()->query("INSERT INTO " . MyPDO::DB_FLAG . "vaisseau (id_joueur,id_planete,type,creato) VALUES (?,?,?,?)",$joueur->id,$deux->id,Vaisseau::CHASSEUR,Tools::now());
        }
    }

    private static function planeteDeux(Joueur $joueur): ?Planete {
        $query = "SELECT id FROM " . MyPDO::DB_FLAG . "planete WHERE id_systeme=? AND id<>? ORDER BY id LIMIT 1";
        $ligne = MyPDO::getInstance()->query($query,$joueur->id_systeme_voisin,self::planeteCible($joueur)->id)[0] ?? null;
        if($ligne === null){
            $idPlanete = Planete::premiereGouvernee($joueur->id);
            $ligne = $idPlanete === null ? null : Ia::sourceLaPlusProche($joueur, new Planete($idPlanete), array(Camp::PIRATES));
        }
        return $ligne === null ? null : new Planete($ligne->id);
    }

    private static function planeteTrois(Joueur $joueur): ?Planete {
        $deux = self::planeteDeux($joueur);
        $query = "SELECT id FROM " . MyPDO::DB_FLAG . "planete WHERE id_systeme=? AND id<>? AND id<>? ORDER BY id LIMIT 1";
        $ligne = MyPDO::getInstance()->query($query,$joueur->id_systeme_voisin,self::planeteCible($joueur)->id,$deux === null ? 0 : $deux->id)[0] ?? null;
        return $ligne === null ? $deux : new Planete($ligne->id);
    }

    private static function preparerPlaneteDeux(Joueur $joueur): void {
        $cible = self::planeteDeux($joueur);
        if($cible === null){
            return;
        }
        $query = "UPDATE " . MyPDO::DB_FLAG . "planete SET id_camp=?, gouverneur=0, pnj_endurance=?, flotte_ia=?, nb_bouclier_planetaire=1, nb_canon_orbital=0, bouclier=?, canon=?, troupes=?, defense_maj=NULL WHERE id=?";
        MyPDO::getInstance()->query($query,Camp::PIRATES,self::GOUVERNEUR_ETOILES,self::ORBITE_CHASSEURS,Planete::bouclierDeBase($cible->slot) + Planete::BOUCLIER_PAR_STRUCTURE,Planete::canonDeBase($cible->slot),Planete::troupesDeBase($cible->slot),$cible->id);
        Decouverte::ajouter($joueur->id, $cible->id_systeme);
    }

    private static function lancerFlotteReduite(Joueur $joueur): void {
        $cible = self::planeteTrois($joueur);
        if($cible === null){
            return;
        }
        MyPDO::getInstance()->query("UPDATE " . MyPDO::DB_FLAG . "planete SET id_camp=?, gouverneur=0, pnj_endurance=NULL, flotte_ia=?, nb_bouclier_planetaire=0, nb_canon_orbital=0, bouclier=0, canon=0, troupes=0, defense_maj=COALESCE(defense_maj, ?) WHERE id=?",Camp::PIRATES,self::FLOTTE_REDUITE,Tools::now(),$cible->id);
        Decouverte::ajouter($joueur->id, $cible->id_systeme);
    }

    private static function lancerMenace(Joueur $joueur): void {
        $idPlanete = Planete::premiereGouvernee($joueur->id);
        if($idPlanete === null){
            return;
        }
        $planete = new Planete($idPlanete);
        $source = Ia::sourceLaPlusProche($joueur, $planete, array(Camp::PIRATES));
        if($source !== null){
            Menace::creer($joueur, $planete, $source);
        }
    }

    private static function entrerDansEtape(Joueur $joueur, int $etape): void {
        if($etape === self::ETAPE_PREPARATION){
            $joueur->crediter(self::PRIME_CREDITS);
            Tools::addMessage('Tutoriel : étape réussie, prime de '.self::PRIME_CREDITS.' '.__('credit', 'credits', self::PRIME_CREDITS).' pour préparer l\'assaut.');
        }
        elseif($etape === self::ETAPE_HEROS){
            $joueur->crediter(self::PRIME_HEROS);
            Tools::addMessage('Tutoriel : prime de '.self::PRIME_HEROS.' '.__('credit', 'credits', self::PRIME_HEROS).' pour recruter un héros.');
        }
        elseif($etape === self::ETAPE_MENACE){
            self::lancerMenace($joueur);
        }
        elseif($etape === self::ETAPE_RESTRICTOR){
            $joueur->crediter(self::PRIME_RESTRICTOR);
            self::lancerFlotteReduite($joueur);
            Tools::addMessage('Tutoriel : prime de '.self::PRIME_RESTRICTOR.' '.__('credit', 'credits', self::PRIME_RESTRICTOR).' pour une usine lourde et un Restrictor.');
        }
        elseif($etape === self::ETAPE_CONQUETE){
            $prime = self::primeDe(Vaisseau::CATALOGUE[Vaisseau::BARGE]['prix'] + Vaisseau::CATALOGUE[Vaisseau::TROUPE]['prix']);
            self::vider($joueur, array(self::planeteDeux($joueur), self::planeteTrois($joueur)));
            $joueur->crediter($prime);
            Tools::addMessage('Tutoriel : prime de '.$prime.' '.__('credit', 'credits', $prime).' pour une barge et une troupe d\'assaut.');
        }
        elseif($etape === self::ETAPE_FERMES){
            self::remplacerParDesFermes($joueur);
            $prime = self::primeDe(self::fermesManquantes($joueur) * Batiment::CATALOGUE[Batiment::FERME]['prix']);
            $joueur->crediter($prime);
            Tools::addMessage('Tutoriel : prime de '.$prime.' '.__('credit', 'credits', $prime).' pour construire les fermes.');
        }
        elseif($etape === self::ETAPE_ANALYSE){
            self::preparerPlaneteDeux($joueur);
            Tools::addMessage('Tutoriel : planète capturée ; la planète voisine vous attend.');
        }
        elseif($etape === self::ETAPE_ASSASSIN){
            $joueur->crediter(self::PRIME_ASSASSIN);
            Tools::addMessage('Tutoriel : orbite dégagée, prime de '.self::PRIME_ASSASSIN.' '.__('credit', 'credits', self::PRIME_ASSASSIN).' pour un assassin et un transport.');
        }
        elseif($etape === Joueur::TUTO_END_STEP){
            MyPDO::getInstance()->query("UPDATE " . MyPDO::DB_FLAG . "joueur SET fin_tuto=? WHERE id=?",Tools::now(),$joueur->id);
            Tools::addMessage('Tutoriel terminé : vous avez appris toutes les mécaniques du jeu. '.strip_tags(self::phraseRevenuParMinute($joueur)));
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
            case self::ETAPE_FLOTTE:
                $query = "SELECT COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "flotte WHERE id_joueur=?";
                return $db->query($query,$joueur->id)[0]->nombre > 0;
            case self::ETAPE_ANALYSE:
                return Analyse::derniere($joueur->id, self::planeteDeux($joueur)->id) !== null;
            case self::ETAPE_ORBITE:
                return array_sum(Combat::defenseurs(self::planeteDeux($joueur))) === 0;
            case self::ETAPE_ASSASSIN:
                return self::planeteDeux($joueur)->pnj_endurance === null;
            case self::ETAPE_SABOTAGE:
                return self::planeteDeux($joueur)->nb_bouclier_planetaire === 0;
            case self::ETAPE_HEROS:
                return self::possedePerso($joueur, Perso::ROLE_HEROS);
            case self::ETAPE_MENACE:
                return Menace::enCours($joueur->id) === null;
            case self::ETAPE_CONQUETE:
                return self::planeteDeux($joueur)->gouverneur === $joueur->id && self::planeteTrois($joueur)->gouverneur === $joueur->id;
            case self::ETAPE_NOMMER:
                return (int) $db->query("SELECT COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "planete WHERE gouverneur=? AND nom_origine IS NOT NULL",$joueur->id)[0]->nombre > 0;
            case self::ETAPE_FERMES:
                return self::planeteSansFermes($joueur) === null;
            case self::ETAPE_RESTRICTOR:
                return (int) $db->query("SELECT tuto_aneantie FROM " . MyPDO::DB_FLAG . "joueur WHERE id=?",$joueur->id)[0]->tuto_aneantie === 1;
            case self::ETAPE_PREPARATION:
                $query = "SELECT type, COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? GROUP BY type";
                return self::atteint($db->query($query,$joueur->id), self::requis());
            case self::ETAPE_ENVOI:
                $cible = self::planeteCible($joueur);
                $query = "SELECT type, COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND id_planete=? AND arrivee IS NULL GROUP BY type";
                return Vaisseau::troupesEnBarge($cible->id, $joueur->id) > 0 && self::atteint($db->query($query,$joueur->id,$cible->id), array(Vaisseau::CHASSEUR => 1));
            case self::ETAPE_ASSAUT:
                return self::planeteCible($joueur)->gouverneur === $joueur->id;
        }
        return false;
    }

    private static function possedePerso(Joueur $joueur, string $role): bool {
        $query = "SELECT COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "perso WHERE id_joueur=? AND role=?";
        return MyPDO::getInstance()->query($query,$joueur->id,$role)[0]->nombre > 0;
    }

    private static function requis(): array {
        return array(Vaisseau::CHASSEUR => self::CHASSEURS_REQUIS, Vaisseau::TROUPE => self::TROUPES_REQUISES, 'fregate' => self::FREGATES_REQUISES, Vaisseau::BARGE => self::BARGES_REQUISES);
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
                return 'Partir dans l\'inconnu';
            case self::ETAPE_PREPARATION:
                return 'Préparer l\'assaut';
            case self::ETAPE_FLOTTE:
                return 'Former une flotte';
            case self::ETAPE_ENVOI:
                return 'Envoyer la flotte';
            case self::ETAPE_ASSAUT:
                return 'Prendre et tenir la planète pirate';
            case self::ETAPE_ANALYSE:
                return 'Reconnaître la planète voisine';
            case self::ETAPE_ORBITE:
                return 'Dégager l\'orbite';
            case self::ETAPE_ASSASSIN:
                return 'Assassiner le gouverneur';
            case self::ETAPE_SABOTAGE:
                return 'Saboter le bouclier planétaire';
            case self::ETAPE_HEROS:
                return 'Recruter un héros';
            case self::ETAPE_BONUS:
                return 'Les bonus de planète';
            case self::ETAPE_DEMOLITION:
                return 'Démolir un bâtiment';
            case self::ETAPE_MENACE:
                return 'Répondre à une menace pirate';
            case self::ETAPE_RESTRICTOR:
                return 'Anéantir une flotte avec un Restrictor';
            case self::ETAPE_CONQUETE:
                return 'Prendre les deux planètes restantes';
            case self::ETAPE_NOMMER:
                return 'Nommer une planète';
            case self::ETAPE_FERMES:
                return 'Des fermes partout';
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
                return 'Votre planète '.$planete->getNom().' peut accueillir des bâtiments. Allez dans le menu Colonies, ouvrez sa fiche et construisez une usine de vaisseaux légers.';
            case self::ETAPE_RECONNAISSANCE:
                return 'Sur la fiche de '.$planete->getNom().', l\'usine de vaisseaux légers peut produire un vaisseau de reconnaissance. Lancez sa production : pendant le tutoriel, les durées sont divisées par '.Joueur::TUTO_DIVISEUR_DUREES.'.';
            case self::ETAPE_EXPLORATION:
                if(self::explorationEnCours($joueur)){
                    return 'Votre vaisseau de reconnaissance est parti dans l\'inconnu : il est en transit. Le compteur « en route » en haut de l\'écran, à côté de la fusée, compte les déplacements de vaisseaux en cours. Quand le vaisseau reviendra avec ce qu\'il a trouvé, le tutoriel continuera.';
                }
                return 'Un système voisin, encore inconnu, existe près du vôtre. Allez dans le menu Flottes : sur '.$planete->getNom().', '.self::action('chercher', 'Chercher').' envoie votre vaisseau de reconnaissance chercher un système dans l\'inconnu, et il revient avec ce qu\'il a trouvé. Pendant le tutoriel il réussit à coup sûr ; plus tard il peut ne rien trouver, voire ne pas revenir.';
            case self::ETAPE_PREPARATION:
                return 'Le système voisin est découvert : sa planète '.self::planeteCible($joueur)->getNom().' est aux mains des Pirates. Préparez l\'assaut sur la fiche de '.$planete->getNom().' : construisez une usine de vaisseaux moyens et une académie, puis produisez '.self::FREGATES_REQUISES.' '.__('fregate', 'fregates', self::FREGATES_REQUISES).', '.self::BARGES_REQUISES.' '.__('barge', 'barges', self::BARGES_REQUISES).', '.self::TROUPES_REQUISES.' '.__('troupe', 'troupes', self::TROUPES_REQUISES).' d\'assaut et '.self::CHASSEURS_REQUIS.' '.__('chasseur', 'chasseurs', self::CHASSEURS_REQUIS).' (réglez le curseur avant '.self::action('produire', 'Produire').' : les unités sortent une par une). La frégate détruit la défense avec les chasseurs qu\'elle embarque ('.Vaisseau::CATALOGUE['fregate']['chasseurs'].' au plus) ; seule la barge débarque les troupes, et seules les troupes embarquées dans une barge débarquent ('.Vaisseau::CATALOGUE['barge']['troupes'].' au plus). Vous les embarquerez vous-même.';
            case self::ETAPE_FLOTTE:
                return 'Une flotte regroupe vos vaisseaux moyens et lourds (frégate, barge, capital). Allez dans le menu Flottes : sur l\'encart de '.$planete->getNom().', '.self::action('creer_flotte', 'Créer une flotte').' (en haut à droite) vous laisse cocher la frégate et la barge, puis '.self::action('valider', 'Enregistrer').'.';
            case self::ETAPE_ENVOI:
                return 'Dans le menu Flottes, ouvrez '.self::action('embarquer', 'Embarquer').' sur la barge de votre flotte : réglez le curseur des troupes sur '.self::TROUPES_REQUISES.' '.__('troupe', 'troupes', self::TROUPES_REQUISES).' et validez ; sur la frégate, réglez celui des chasseurs sur '.self::CHASSEURS_REQUIS.' et validez. Un seul bouton valide tous les curseurs du panneau, et un curseur laissé à 0 n\'embarque rien. Ensuite '.self::action('voyager', 'Voyager').' sur votre flotte : touchez le système voisin, puis la planète '.self::planeteCible($joueur)->getNom().', et '.self::action('voyager', 'Voyager ici').'. À l\'arrivée, la défense de la planète engage votre flotte automatiquement : le combat spatial n\'est plus un choix.';
            case self::ETAPE_ASSAUT:
                return 'Votre flotte est arrivée : le combat contre la défense de '.self::planeteCible($joueur)->getNom().' s\'est déclenché tout seul, les chasseurs embarqués sur la frégate combattent avec elle. Le rapport de bataille s\'ouvre à l\'arrivée : "Résoudre" le déroule en direct. Pendant le tutoriel vous le gagnez d\'avance. Une fois la défense détruite, ouvrez la fiche de la planète ('.self::action('info', 'Fiche').' sur la carte) et '.self::action('debarquement', 'Débarquer').' les troupes de vos barges. Les troupes débarquées deviennent l\'armée d\'occupation : tant qu\'elles sont présentes, la révolte de la planète ne progresse plus. Si l\'assaut échoue, produisez de nouvelles troupes et recommencez.';
            case self::ETAPE_ANALYSE:
                $deux = self::planeteDeux($joueur);
                return 'Planète capturée et tenue. Inutile de faire de la diplomatie ni de la rallier à votre cause : tant que votre garnison y reste, ses bâtiments produisent, avec un léger malus de temps ('.Planete::MALUS_OCCUPATION_POURCENT.' %). Si la garnison disparaît ou est détruite, la planète redevient neutre, élit un gouverneur une fois sur deux et reconstruit ses défenses. Place à la planète voisine '.($deux === null ? '' : $deux->getNom()).' : elle a un gouverneur. Envoyez-y votre vaisseau de reconnaissance (menu Flottes, '.self::action('reconnaitre', 'Reconnaître').') : une reconnaissance ne déclenche aucun combat (un joueur adverse peut pourtant l\'attaquer) et, à l\'arrivée, elle analyse la planète : garnison, vaisseaux en orbite, bâtiments et gouverneur, qui restaient inconnus.';
            case self::ETAPE_ORBITE:
                return 'L\'analyse montre un gouverneur, un bouclier planétaire et seulement '.self::ORBITE_CHASSEURS.' chasseur en orbite. Les chasseurs de votre frégate sont à bord : dans le menu Flottes, ouvrez '.self::action('decharger', 'Débarquer').' sur la frégate, poussez le curseur des chasseurs au maximum et validez : ils descendent de la frégate. Dans un même système, les vaisseaux légers peuvent se déplacer seuls : envoyez uniquement les chasseurs sur la planète voisine ('.self::action('voyager', 'Voyager').'), l\'arrivée déclenche le combat. Une orbite vide réduit fortement les chances que l\'espion se fasse attraper : chaque vaisseau en orbite retire '.Perso::PENALITE_ORBITE_PAR_VAISSEAU_POURCENT.' % à ses chances.';
            case self::ETAPE_ASSASSIN:
                return 'Orbite dégagée. Tant qu\'un gouverneur est en place, on ne peut ni gouverner la planète ni baisser sa révolte : il faut l\'éliminer. Recrutez un assassin à l\'académie de '.$planete->getNom().' ('.Perso::ROLES[Perso::ROLE_ASSASSIN]['prix'].' cr), produisez un transport personnel, faites-y monter l\'assassin (sa fiche, '.self::action('embarquer', 'Monter dans un transport').'), envoyez le transport sur la planète voisine ('.self::action('voyager', 'Voyager').', même système), puis '.self::action('assassin', 'Assassiner').' le gouverneur depuis la fiche de l\'assassin.';
            case self::ETAPE_SABOTAGE:
                return 'Le gouverneur est éliminé, mais le bouclier planétaire interdit encore tout débarquement de troupes : tant qu\'il existe, la flotte ne peut pas débarquer. Les espions peuvent saboter les bâtiments : sur la fiche de l\'assassin, '.self::action('saboter', 'Saboter').' le bouclier planétaire.';
            case self::ETAPE_HEROS:
                return 'Recrutez maintenant un héros à l\'académie de '.$planete->getNom().' ('.Perso::ROLES[Perso::ROLE_HEROS]['prix'].' cr). Présent sur une planète, il ajoute '.Perso::HEROS_SUCCESS_PER_STAR_PERCENT.' % de chances de réussite par étoile de diplomatie aux diplomates qui s\'y trouvent, et '.Combat::HEROS_BONUS_PAR_ETOILE_POURCENT.' % de défense par étoile de force face aux menaces pirates.';
            case self::ETAPE_BONUS:
                return 'Certaines planètes portent un bonus, visible sur leur fiche, qui profite à leur gouverneur : '.self::texteBonus().' Quand vous avez compris, validez.';
            case self::ETAPE_DEMOLITION:
                return 'Sur la fiche d\'une de vos planètes, '.self::action('demolir', 'Démolir').' supprime un bâtiment et rembourse '.Batiment::REFUND_PERCENT.' % de son prix : l\'emplacement redevient libre. C\'est impossible tant qu\'une production occupe toutes les usines de ce type. Quand vous avez compris, validez.';
            case self::ETAPE_MENACE:
                return 'Une flotte pirate menace '.$planete->getNom().'. Allez dans le menu Flottes : '.self::action('combattre', 'Combattre').' l\'oppose à vos vaisseaux présents sur la planète (bonus des héros et de la citadelle) ; '.self::action('laisser', 'Laisser').' laisse les Pirates détourner '.Detournement::POURCENT.' % de '.Detournement::DUREE_HEURES.' h de revenus. Sans réponse sous '.intdiv(Menace::EXPIRATION_SECONDES, Planete::SECONDS_PER_HOUR).' h, ils détournent. Répondez pour continuer.';
            case self::ETAPE_RESTRICTOR:
                $cible = self::planeteTrois($joueur);
                $deux = self::planeteDeux($joueur);
                return 'Une flotte pirate réduite ('.self::FLOTTE_REDUITE.' '.__('chasseur', 'chasseurs', self::FLOTTE_REDUITE).') est stationnée sur '.($cible === null ? 'une planète voisine' : $cible->getNom()).'. Une flotte battue s\'enfuit vers la planète alliée ou neutre la plus proche : le Restrictor l\'en empêche, tant qu\'il vit dans votre flotte. Construisez une usine de vaisseaux lourds sur '.$planete->getNom().' et produisez un Restrictor ('.Vaisseau::CATALOGUE[Vaisseau::RESTRICTOR]['prix'].' cr) : il embarque '.Vaisseau::CATALOGUE[Vaisseau::RESTRICTOR][Vaisseau::CAPACITE_CHASSEURS].' '.__('chasseur', 'chasseurs', Vaisseau::CATALOGUE[Vaisseau::RESTRICTOR][Vaisseau::CAPACITE_CHASSEURS]).' et '.Vaisseau::CATALOGUE[Vaisseau::RESTRICTOR][Vaisseau::CAPACITE_TROUPES].' '.__('troupe', 'troupes', Vaisseau::CATALOGUE[Vaisseau::RESTRICTOR][Vaisseau::CAPACITE_TROUPES]).' d\'assaut. Menu Flottes : '.self::action('voyager', 'Voyager').' avec lui sur '.($deux === null ? 'la planète voisine' : $deux->getNom()).', déjà nettoyée, où vos chasseurs vous attendent. Là, '.self::action('embarquer', 'Embarquer').' les '.Vaisseau::CATALOGUE[Vaisseau::RESTRICTOR][Vaisseau::CAPACITE_CHASSEURS].' chasseurs sur le Restrictor (curseur des chasseurs sur '.Vaisseau::CATALOGUE[Vaisseau::RESTRICTOR][Vaisseau::CAPACITE_CHASSEURS].', celui des troupes à 0, puis validez), et '.self::action('voyager', 'Voyager').' sur '.($cible === null ? 'la planète pirate' : $cible->getNom()).' : l\'arrivée déclenche le combat tout seul, et pendant le tutoriel vous le gagnez d\'avance.';
            case self::ETAPE_CONQUETE:
                $deux = self::planeteDeux($joueur);
                $trois = self::planeteTrois($joueur);
                return $deux->getNom().' et '.$trois->getNom().' ne sont plus défendues, mais personne ne les gouverne : les Pirates peuvent encore les reprendre. Prenez-les, comme la première. Menu Flottes : produisez une barge et une troupe d\'assaut sur '.$planete->getNom().' (prime comprise), '.self::action('embarquer', 'Embarquer').' la troupe dans la barge, puis '.self::action('voyager', 'Voyager').' sur '.$deux->getNom().' et '.self::action('debarquement', 'Débarquer').'. Une troupe de renfort vous y attend ensuite : embarquez-la, voyagez sur '.$trois->getNom().' et débarquez. Une barge débarque toutes ses troupes sur une seule planète, d\'où le renfort ; pendant le tutoriel, le débarquement est gagné d\'avance.';
            case self::ETAPE_NOMMER:
                return 'Vous gouvernez les trois planètes du système voisin. Un gouverneur dont un perso se trouve sur la planète peut la renommer : ouvrez la fiche d\'une planète où l\'un de vos persos est présent (l\'assassin est resté sur '.self::planeteDeux($joueur)->getNom().') et '.self::action('renommer', 'Renommer la planète').'. Si le gouverneur perd la planète, elle reprend son nom d\'origine.';
            case self::ETAPE_FERMES:
                return 'Chaque ferme rapporte '.Batiment::FERME_INCOME.' cr/h pour '.Batiment::CATALOGUE[Batiment::FERME]['maintenance'].' cr/h de maintenance. Une prime couvre le coût : sur chacune de vos trois planètes du système voisin (menu Colonies, puis la fiche de la planète), construisez des fermes sur tous les emplacements, rien d\'autre. '.self::phraseRevenuParMinute($joueur);
        }
        return '';
    }

    private static function action(string $icone, string $libelle): string {
        return '<span class="tuto-action">'.Icone::html($icone, Vue::ICONE_TEXTE_TAILLE).' '.htmlspecialchars($libelle).'</span>';
    }

    private static function texteBonus(): string {
        $bonus = array();
        foreach(array_keys(Bonus::CATALOGUE) as $cle){
            array_push($bonus, Bonus::libelle($cle).' ('.Bonus::description($cle).')');
        }
        return implode(' ; ', $bonus).'.';
    }

    public static function texteIcones(): string {
        return 'Crédits : votre monnaie, dépensée en bâtiments, vaisseaux, troupes et recrutements. '
            .'Cr/h : votre revenu net par heure. Chaque planète rapporte '.Batiment::BASE_INCOME.' cr/h, chaque ferme '.Batiment::FERME_INCOME.' de plus ; hors tutoriel, chaque bâtiment coûte sa maintenance, et un revenu négatif interdit de construire. Construisez des fermes, démolissez l\'inutile. '
            .'Planètes : le nombre de planètes que vous gouvernez ; conquérez-en par la force ou la diplomatie. '
            .'En route : le nombre de déplacements de vaisseaux en cours, visibles sur la page Flottes.';
    }

}
