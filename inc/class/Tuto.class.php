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
    const ETAPES_A_VALIDER = array(self::ETAPE_BONUS, self::ETAPE_DEMOLITION);
    const PRIME_HEROS = 350;
    const PRIME_CREDITS = 800;
    const PRIME_ASSASSIN = 400;
    const PRIME_RESTRICTOR = 1000;
    const FLOTTE_REDUITE = 5;
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
    const CLE_PERSO = 'perso-';
    const CLE_COMPRENDRE = 'comprendre';

    public static function avancer(Joueur $joueur): void {
        if($joueur->etape_tuto >= self::ETAPE_USINE && $joueur->enTuto() && $joueur->id_systeme_voisin === null){
            $idPlanete = Planete::premiereGouvernee($joueur->id);
            Univers::creerVoisin($joueur, $idPlanete === null ? null : (new Planete($idPlanete))->systeme);
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
        foreach(array_reverse($route) as $niveau){
            if($niveau['page'] === $page){
                return $niveau['cle'] === null ? '' : ' data-tuto-cible="'.$niveau['cle'].'"';
            }
        }
        return ' data-tuto-menu="'.$route[0]['page'].'"';
    }

    private static function niveau(string $page, ?string $cle): array {
        return array('page' => $page, 'cle' => $cle);
    }

    private static function routeVersPlanete(Planete $mere, string $cle): array {
        return array(self::niveau('listePlanete', self::CLE_PLANETE.$mere->id), self::niveau('affichePlanete', $cle));
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
                return self::routeVersPlanete($mere, self::CLE_PRODUIRE.Vaisseau::RECONNAISSANCE);
            case self::ETAPE_EXPLORATION:
                return array(self::niveau('listeVaisseau', 'chercher'));
            case self::ETAPE_PREPARATION:
                return self::routeVersPlanete($mere, self::cleDePreparation($joueur, $mere));
            case self::ETAPE_FLOTTE:
                return array(self::niveau('listeVaisseau', 'creer-flotte enregistrer-flotte'));
            case self::ETAPE_ENVOI:
                $pret = self::nombreDeVaisseaux($joueur, Vaisseau::TROUPE, true) >= self::TROUPES_REQUISES && self::nombreDeVaisseaux($joueur, Vaisseau::CHASSEUR, true) >= self::CHASSEURS_REQUIS;
                return array(self::niveau('listeVaisseau', $pret ? 'voyager-flotte' : 'embarquer'));
            case self::ETAPE_ASSAUT:
                return array(self::niveau('carte', null), self::niveau('affichePlanete', 'attaque_flotte debarquement'));
            case self::ETAPE_ANALYSE:
                return array(self::niveau('listeVaisseau', self::CLE_VOYAGER.Vaisseau::RECONNAISSANCE));
            case self::ETAPE_ORBITE:
                return array(self::niveau('listeVaisseau', self::nombreDeVaisseaux($joueur, Vaisseau::CHASSEUR, true) > 0 ? 'decharger' : self::CLE_VOYAGER.Vaisseau::CHASSEUR));
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
        }
        return array(self::niveau($page, self::CLE_COMPRENDRE));
    }

    private static function cleDePreparation(Joueur $joueur, Planete $mere): string {
        if($mere->nombre(Batiment::USINE_MOYENNE) < 1){
            return self::CLE_CONSTRUIRE.Batiment::USINE_MOYENNE;
        }
        if($mere->nombre(Batiment::ACADEMIE) < 1){
            return self::CLE_CONSTRUIRE.Batiment::ACADEMIE;
        }
        $stock = Vaisseau::stock($mere->id, $joueur->id);
        foreach(self::requis() as $type => $minimum){
            if(($stock[$type] ?? 0) < $minimum){
                return self::CLE_PRODUIRE.$type;
            }
        }
        return self::CLE_PRODUIRE.Vaisseau::TROUPE;
    }

    private static function routeDAssassin(Joueur $joueur, Planete $mere): array {
        if(!self::possedePerso($joueur, Perso::ROLE_ASSASSIN)){
            return self::routeVersPlanete($mere, self::CLE_RECRUTER.Perso::ROLE_ASSASSIN);
        }
        if(self::nombreDeVaisseaux($joueur, Vaisseau::TRANSPORT_PERSO, false) < 1){
            return self::routeVersPlanete($mere, self::CLE_PRODUIRE.Vaisseau::TRANSPORT_PERSO);
        }
        return array(self::niveau('listePerso', self::CLE_PERSO.Perso::ROLE_ASSASSIN), self::niveau('affichePerso', 'assassin embarquer'), self::niveau('listeVaisseau', self::CLE_VOYAGER.Vaisseau::TRANSPORT_PERSO));
    }

    private static function routeDeRestrictor(Joueur $joueur, Planete $mere): array {
        if($mere->nombre(Batiment::USINE_LOURDE) < 1){
            return self::routeVersPlanete($mere, self::CLE_CONSTRUIRE.Batiment::USINE_LOURDE);
        }
        if(self::nombreDeVaisseaux($joueur, Vaisseau::RESTRICTOR, false) < 1){
            return self::routeVersPlanete($mere, self::CLE_PRODUIRE.Vaisseau::RESTRICTOR);
        }
        return array(self::niveau('listeVaisseau', 'creer-flotte enregistrer-flotte voyager-flotte'));
    }

    private static function planeteDeux(Joueur $joueur): ?Planete {
        $query = "SELECT id FROM " . MyPDO::DB_FLAG . "planete WHERE id_systeme=? AND id<>? AND gouverneur=0 ORDER BY id LIMIT 1";
        $ligne = MyPDO::getInstance()->query($query,$joueur->id_systeme_voisin,self::planeteCible($joueur)->id)[0] ?? null;
        if($ligne === null){
            $idPlanete = Planete::premiereGouvernee($joueur->id);
            $ligne = $idPlanete === null ? null : Ia::sourceLaPlusProche($joueur, new Planete($idPlanete), array(Camp::PIRATES));
        }
        return $ligne === null ? null : new Planete($ligne->id);
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
        $cible = self::planeteDeux($joueur);
        if($cible === null){
            return;
        }
        MyPDO::getInstance()->query("UPDATE " . MyPDO::DB_FLAG . "planete SET flotte_ia=?, defense_maj=COALESCE(defense_maj, ?) WHERE id=?",self::FLOTTE_REDUITE,Tools::now(),$cible->id);
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
            Tools::addMessage('Tutoriel terminé : vous avez appris toutes les mécaniques du jeu.');
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
                return 'Un système voisin, encore inconnu, existe près du vôtre. Allez dans le menu Flottes : sur '.$planete->getNom().', '.self::action('chercher', 'Chercher').' envoie votre vaisseau de reconnaissance chercher un système dans l\'inconnu, et il revient avec ce qu\'il a trouvé. Pendant le tutoriel il réussit à coup sûr ; plus tard il peut ne rien trouver, voire ne pas revenir.';
            case self::ETAPE_PREPARATION:
                return 'Le système voisin est découvert : sa planète '.self::planeteCible($joueur)->getNom().' est aux mains des Pirates. Préparez l\'assaut sur la fiche de '.$planete->getNom().' : construisez une usine de vaisseaux moyens et une académie, puis produisez '.self::FREGATES_REQUISES.' '.__('fregate', 'fregates', self::FREGATES_REQUISES).', '.self::BARGES_REQUISES.' '.__('barge', 'barges', self::BARGES_REQUISES).', '.self::TROUPES_REQUISES.' '.__('troupe', 'troupes', self::TROUPES_REQUISES).' d\'assaut et '.self::CHASSEURS_REQUIS.' '.__('chasseur', 'chasseurs', self::CHASSEURS_REQUIS).' (réglez le curseur avant '.self::action('produire', 'Produire').' : les unités sortent une par une). La frégate détruit la défense avec les chasseurs qu\'elle embarque ('.Vaisseau::CATALOGUE['fregate']['chasseurs'].' au plus) ; seule la barge débarque les troupes, et seules les troupes embarquées dans une barge débarquent ('.Vaisseau::CATALOGUE['barge']['troupes'].' au plus). Vous les embarquerez vous-même.';
            case self::ETAPE_FLOTTE:
                return 'Une flotte regroupe vos vaisseaux moyens et lourds (frégate, barge, capital). Allez dans le menu Flottes : sur l\'encart de '.$planete->getNom().', '.self::action('creer_flotte', 'Créer une flotte').' (en haut à droite) vous laisse cocher la frégate et la barge, puis '.self::action('valider', 'Enregistrer').'.';
            case self::ETAPE_ENVOI:
                return 'Dans le menu Flottes, ouvrez '.self::action('embarquer', 'Embarquer').' sur la barge de votre flotte : réglez le curseur sur '.self::TROUPES_REQUISES.' '.__('troupe', 'troupes', self::TROUPES_REQUISES).' et validez ; sur la frégate, réglez celui des chasseurs sur '.self::CHASSEURS_REQUIS.' et validez. Ensuite '.self::action('voyager', 'Voyager').' sur votre flotte : touchez le système voisin et '.self::action('voyager', 'Voyager ici').' sur '.self::planeteCible($joueur)->getNom().'.';
            case self::ETAPE_ASSAUT:
                return 'Votre flotte est arrivée. Ouvrez la fiche de '.self::planeteCible($joueur)->getNom().' : '.self::action('attaque_flotte', 'Attaquer').' avec votre flotte pour détruire sa défense (les chasseurs embarqués sur la frégate combattent avec elle, pas besoin de les sortir), puis '.self::action('debarquement', 'Débarquer').' les troupes de vos barges. Les troupes débarquées deviennent l\'armée d\'occupation : tant qu\'elles sont présentes, la révolte de la planète ne progresse plus. Si l\'assaut échoue, produisez de nouvelles troupes et recommencez.';
            case self::ETAPE_ANALYSE:
                $deux = self::planeteDeux($joueur);
                return 'Planète capturée et tenue. Inutile de faire de la diplomatie ni de la rallier à votre cause : tant que votre garnison y reste, ses bâtiments produisent, avec un léger malus de temps ('.Planete::MALUS_OCCUPATION_POURCENT.' %). Si la garnison disparaît ou est détruite, la planète redevient neutre, élit un gouverneur une fois sur deux et reconstruit ses défenses. Place à la planète voisine '.($deux === null ? '' : $deux->getNom()).' : elle a un gouverneur. Envoyez-y votre vaisseau de reconnaissance (menu Flottes, '.self::action('reconnaitre', 'Reconnaître').') : une reconnaissance ne déclenche aucun combat (un joueur adverse peut pourtant l\'attaquer) et, à l\'arrivée, elle analyse la planète : garnison, vaisseaux en orbite, bâtiments et gouverneur, qui restaient inconnus.';
            case self::ETAPE_ORBITE:
                return 'L\'analyse montre un gouverneur, un bouclier planétaire et seulement '.self::ORBITE_CHASSEURS.' chasseur en orbite. Les chasseurs de votre frégate sont à bord : dans le menu Flottes, '.self::action('decharger', 'Débarquer').' les fait descendre de la frégate. Dans un même système, les vaisseaux légers peuvent se déplacer seuls : envoyez uniquement les chasseurs sur la planète voisine ('.self::action('voyager', 'Voyager').'), l\'arrivée déclenche le combat. Une orbite vide réduit fortement les chances que l\'espion se fasse attraper : chaque vaisseau en orbite retire '.Perso::PENALITE_ORBITE_PAR_VAISSEAU_POURCENT.' % à ses chances.';
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
                $cible = self::planeteDeux($joueur);
                return 'Une flotte pirate réduite ('.self::FLOTTE_REDUITE.' '.__('chasseur', 'chasseurs', self::FLOTTE_REDUITE).') est stationnée sur '.($cible === null ? 'une planète voisine' : $cible->getNom()).'. Une flotte battue qui a perdu l\'essentiel de ses vaisseaux s\'enfuit vers la planète alliée ou neutre la plus proche. Le Restrictor l\'en empêche : tant qu\'il vit dans votre flotte, l\'ennemi ne peut pas fuir. Construisez une usine de vaisseaux lourds sur '.$planete->getNom().' et produisez un Restrictor ('.Vaisseau::CATALOGUE[Vaisseau::RESTRICTOR]['prix'].' cr), regroupez-le avec votre frégate dans une flotte (embarquez-y vos chasseurs : ils protègent vos grands vaisseaux, car les chasseurs ennemis frappent doublement les capitaux), puis voyagez vers cette planète : l\'arrivée déclenche le combat, '.self::action('resoudre', 'Résoudre').' le joue.';
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
