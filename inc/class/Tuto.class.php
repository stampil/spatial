<?php

class Tuto {

    const ETAPE_USINE = 2;
    const ETAPE_RECONNAISSANCE = 3;
    const ETAPE_EXPLORATION = 4;
    const ETAPE_PREPARATION = 5;
    const ETAPE_FLOTTE = 6;
    const ETAPE_ENVOI = 7;
    const ETAPE_ASSAUT = 8;
    const ETAPE_ASSASSIN = 9;
    const ETAPE_HEROS = 10;
    const ETAPE_BONUS = 11;
    const ETAPE_DEMOLITION = 12;
    const ETAPE_MENACE = 13;
    const ETAPES_A_VALIDER = array(self::ETAPE_BONUS, self::ETAPE_DEMOLITION);
    const PRIME_HEROS = 350;
    const PRIME_CREDITS = 800;
    const PRIME_ASSASSIN = 250;
    const CHASSEURS_REQUIS = 3;
    const TROUPES_REQUISES = 3;
    const FREGATES_REQUISES = 1;

    public static function avancer(Joueur $joueur): void {
        if($joueur->etape_tuto >= self::ETAPE_USINE && $joueur->enTuto() && $joueur->id_systeme_voisin === null){
            $idPlanete = Planete::premiereGouvernee($joueur->id);
            Univers::creerVoisin($joueur, $idPlanete === null ? null : (new Planete($idPlanete))->systeme);
        }
        while($joueur->etape_tuto >= self::ETAPE_USINE && $joueur->enTuto() && self::objectifAtteint($joueur)){
            self::passerALEtapeSuivante($joueur);
        }
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
            Tools::addMessage('Tutoriel : étape réussie, prime de '.self::PRIME_CREDITS.' crédits pour préparer l\'assaut.');
        }
        elseif($etape === self::ETAPE_HEROS){
            $joueur->crediter(self::PRIME_HEROS);
            Tools::addMessage('Tutoriel : prime de '.self::PRIME_HEROS.' crédits pour recruter un héros.');
        }
        elseif($etape === self::ETAPE_MENACE){
            self::lancerMenace($joueur);
        }
        elseif($etape === self::ETAPE_ASSASSIN){
            $joueur->crediter(self::PRIME_ASSASSIN);
            Tools::addMessage('Tutoriel : planète capturée, prime de '.self::PRIME_ASSASSIN.' crédits pour recruter un assassin.');
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
            case self::ETAPE_ASSASSIN:
                return self::possedePerso($joueur, Perso::ROLE_ASSASSIN);
            case self::ETAPE_HEROS:
                return self::possedePerso($joueur, Perso::ROLE_HEROS);
            case self::ETAPE_MENACE:
                return Menace::enCours($joueur->id) === null;
            case self::ETAPE_PREPARATION:
                $query = "SELECT type, COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? GROUP BY type";
                return self::atteint($db->query($query,$joueur->id), self::requis());
            case self::ETAPE_ENVOI:
                $query = "SELECT type, COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND id_planete=? AND arrivee IS NULL GROUP BY type";
                return self::atteint($db->query($query,$joueur->id,self::planeteCible($joueur)->id), array(Vaisseau::CHASSEUR => 1, Vaisseau::TROUPE => 1));
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
        return array(Vaisseau::CHASSEUR => self::CHASSEURS_REQUIS, Vaisseau::TROUPE => self::TROUPES_REQUISES, 'fregate' => self::FREGATES_REQUISES);
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
            case self::ETAPE_ASSASSIN:
                return 'Recruter un assassin';
            case self::ETAPE_HEROS:
                return 'Recruter un héros';
            case self::ETAPE_BONUS:
                return 'Les bonus de planète';
            case self::ETAPE_DEMOLITION:
                return 'Démolir un bâtiment';
            case self::ETAPE_MENACE:
                return 'Répondre à une menace pirate';
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
                return 'Un système voisin, encore inconnu, existe près du vôtre. Ouvrez <a href="?p=listeVaisseau">Flottes</a>, « Donner un ordre » sur '.$planete->getNom().', choisissez 1 vaisseau de reconnaissance puis « Choisir la destination sur la carte » et touchez « Explorer » : il part dans l\'inconnu et revient avec ce qu\'il a trouvé. Pendant le tutoriel il réussit à coup sûr ; plus tard il peut ne rien trouver, voire ne pas revenir.';
            case self::ETAPE_PREPARATION:
                return 'Le système voisin est découvert : sa planète '.self::planeteCible($joueur)->getNom().' est aux mains des Pirates. Préparez l\'assaut sur la fiche de '.$planete->getNom().' : construisez une usine de vaisseaux moyens et une académie, puis produisez '.self::FREGATES_REQUISES.' frégate, '.self::TROUPES_REQUISES.' troupes d\'assaut et '.self::CHASSEURS_REQUIS.' chasseurs (choisissez le nombre avant « Produire » : les unités sortent une par une). Les troupes et les chasseurs ne voyagent jamais seuls entre systèmes : la frégate les embarque ('.Vaisseau::CATALOGUE['fregate']['troupes'].' troupes et '.Vaisseau::CATALOGUE['fregate']['chasseurs'].' chasseurs).';
            case self::ETAPE_FLOTTE:
                return 'Une flotte regroupe vos vaisseaux moyens et lourds (frégate, barge, capital). Ouvrez <a href="?p=listeVaisseau">Flottes</a>, « Créer une flotte » sur '.$planete->getNom().' : choisissez la frégate, donnez un nom à la flotte.';
            case self::ETAPE_ENVOI:
                return 'Sur <a href="?p=listeVaisseau">Flottes</a>, « Donner un ordre » sur '.$planete->getNom().' : cochez votre flotte, indiquez '.self::TROUPES_REQUISES.' troupes et '.self::CHASSEURS_REQUIS.' chasseurs (la frégate les embarque), puis « Choisir la destination sur la carte » : touchez le système voisin et « Envoyer ici » sur '.self::planeteCible($joueur)->getNom().'.';
            case self::ETAPE_ASSAUT:
                return 'Votre flotte est arrivée. Ouvrez la fiche de '.self::planeteCible($joueur)->getNom().' : attaquez avec vos vaisseaux pour détruire sa défense, puis débarquez vos troupes. Les troupes débarquées deviennent l\'armée d\'occupation : tant qu\'elles sont présentes, la révolte de la planète ne progresse plus. Si l\'assaut échoue, produisez de nouvelles troupes et recommencez.';
            case self::ETAPE_ASSASSIN:
                return 'Planète capturée et tenue. Recrutez un assassin à l\'académie de '.$planete->getNom().' ('.Perso::ROLES[Perso::ROLE_ASSASSIN]['prix'].' cr) : il peut éliminer un perso adverse présent sur la même planète (chance de base '.Perso::ASSASSINAT_BASE_PERCENT.' %, '.Perso::ASSASSINAT_PER_STAR_PERCENT.' % par étoile d\'écart entre sa force et l\'endurance de la cible).';
            case self::ETAPE_HEROS:
                return 'Recrutez maintenant un héros à l\'académie de '.$planete->getNom().' ('.Perso::ROLES[Perso::ROLE_HEROS]['prix'].' cr). Présent sur une planète, il ajoute '.Perso::HEROS_SUCCESS_PER_STAR_PERCENT.' % de chances de réussite par étoile de diplomatie aux diplomates qui s\'y trouvent, et '.Combat::HEROS_BONUS_PAR_ETOILE_POURCENT.' % de défense par étoile de force face aux menaces pirates.';
            case self::ETAPE_BONUS:
                return 'Certaines planètes portent un bonus, visible sur leur fiche, qui profite à leur gouverneur : '.self::texteBonus().' Quand vous avez compris, validez.';
            case self::ETAPE_DEMOLITION:
                return 'Sur la fiche d\'une de vos planètes, « Démolir » supprime un bâtiment et rembourse '.Batiment::REFUND_PERCENT.' % de son prix : l\'emplacement redevient libre. C\'est impossible tant qu\'une production occupe toutes les usines de ce type. Quand vous avez compris, validez.';
            case self::ETAPE_MENACE:
                return 'Une flotte pirate menace '.$planete->getNom().'. Ouvrez <a href="?p=listeVaisseau">Flottes</a> : « Combattre » l\'oppose à vos vaisseaux présents sur la planète (bonus des héros et de la citadelle) ; « Laisser faire » laisse les Pirates détourner '.Detournement::POURCENT.' % de '.Detournement::DUREE_HEURES.' h de revenus. Sans réponse sous '.intdiv(Menace::EXPIRATION_SECONDES, Planete::SECONDS_PER_HOUR).' h, ils détournent. Répondez pour terminer le tutoriel.';
        }
        return '';
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
