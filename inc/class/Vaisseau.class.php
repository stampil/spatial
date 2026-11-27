<?php

class Vaisseau {

    const TRAJET_SECONDES_PAR_UNITE = 1;
    const PERCENT = 100;
    const ILLUSTRATION_DECOUVERTE = 'evenement-decouverte';
    const ILLUSTRATION_RIEN = 'evenement-rien';
    const ILLUSTRATION_PERDU = 'evenement-perdu';
    const ILLUSTRATION_ANALYSE = 'evenement-analyse';
    const RECONNAISSANCE = 'reconnaissance';
    const CHASSEUR = 'chasseur';
    const BARGE = 'barge';
    const TROUPE = 'troupe_assaut';
    const RESTRICTOR = 'restrictor';
    const DESTINATION_EXPLORATION = 'explorer';
    const EXPLORATION_SECONDES = 900;
    const EXPLORATION_PERTE_POURCENT = 20;
    const EXPLORATION_DECOUVERTE_POURCENT = 50;
    const TRANSPORT_PERSO = 'transport_perso';
    const CAPACITE_TRANSPORT_PERSO = 2;
    const NOM_LONGUEUR_MAX = 32;
    const CLE_PREFIXE = 'prefixe';
    const CAPACITE_TROUPES = 'troupes';
    const TROUPES_EN_BARGE = " FROM " . MyPDO::DB_FLAG . "vaisseau e JOIN " . MyPDO::DB_FLAG . "vaisseau h ON h.id=e.id_hote WHERE e.id_joueur=? AND e.id_planete=? AND e.type=? AND e.arrivee IS NULL AND h.type=? AND h.arrivee IS NULL";
    const CAPACITE_CHASSEURS = 'chasseurs';

    const CATALOGUE = array(
        'chasseur' => array('icone' => 'chasseur', 'nom' => 'Chasseur', 'usine' => Batiment::USINE_LEGERE, 'prix' => 40, 'duree' => 30, 'vitesse' => 100, 'force' => 5, 'troupes' => 0, 'chasseurs' => 0),
        'reconnaissance' => array('icone' => 'reconnaissance', 'nom' => 'Vaisseau de reconnaissance', 'usine' => Batiment::USINE_LEGERE, 'prix' => 30, 'duree' => 30, 'vitesse' => 50, 'force' => 0, 'troupes' => 0, 'chasseurs' => 0),
        'transport_perso' => array('icone' => 'transport', 'nom' => 'Transport personnel', 'usine' => Batiment::USINE_LEGERE, 'prix' => 50, 'duree' => 45, 'vitesse' => 60, 'force' => 0, 'troupes' => 0, 'chasseurs' => 0),
        'fregate' => array('icone' => 'fregate', 'nom' => 'Frégate', 'usine' => Batiment::USINE_MOYENNE, 'prix' => 120, 'duree' => 90, 'vitesse' => 100, 'force' => 15, 'troupes' => 10, 'chasseurs' => 3, 'prefixe' => 'Frégate'),
        'barge' => array('icone' => 'barge', 'nom' => 'Barge de débarquement', 'usine' => Batiment::USINE_MOYENNE, 'prix' => 150, 'duree' => 120, 'vitesse' => 200, 'force' => 0, 'troupes' => 10, 'chasseurs' => 0, 'prefixe' => 'Barge'),
        'capital' => array('icone' => 'fusee', 'nom' => 'Vaisseau capital', 'usine' => Batiment::USINE_LOURDE, 'prix' => 400, 'duree' => 300, 'vitesse' => 150, 'force' => 50, 'troupes' => 20, 'chasseurs' => 6, 'prefixe' => 'Capital'),
        'restrictor' => array('icone' => 'restrictor', 'nom' => 'Restrictor', 'usine' => Batiment::USINE_LOURDE, 'prix' => 500, 'duree' => 300, 'vitesse' => 120, 'force' => 20, 'troupes' => 0, 'chasseurs' => 0, 'prefixe' => 'Restrictor'),
        'troupe_assaut' => array('icone' => 'troupe', 'nom' => 'Troupe d\'assaut', 'usine' => Batiment::ACADEMIE, 'prix' => 60, 'duree' => 20, 'vitesse' => 200, 'force' => 10, 'troupes' => 0, 'chasseurs' => 0),
    );

    public static function typesDeLUsine(string $usine): array {
        $types = array();
        foreach(self::CATALOGUE as $type => $infos){
            if($infos['usine'] === $usine){
                $types[] = $type;
            }
        }
        return $types;
    }

    public static function enProduction(int $planeteId): array {
        $query = "SELECT * FROM " . MyPDO::DB_FLAG . "production WHERE id_planete=? ORDER BY fin";
        return MyPDO::getInstance()->query($query,$planeteId);
    }

    public static function nombreEnProduction(int $planeteId, string $usine): int {
        $types = self::typesDeLUsine($usine);
        if(count($types) === 0){
            return 0;
        }
        $marques = implode(',', array_fill(0, count($types), '?'));
        $query = "SELECT COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "production WHERE id_planete=? AND type IN ($marques)";
        return (int) MyPDO::getInstance()->query($query,$planeteId,...$types)[0]->nombre;
    }

    public static function stock(int $planeteId, int $joueurId, ?int $flotteId = null): array {
        $query = "SELECT type, COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_planete=? AND id_joueur=? AND arrivee IS NULL AND id_flotte <=> ? GROUP BY type";
        $stock = array();
        foreach(MyPDO::getInstance()->query($query,$planeteId,$joueurId,$flotteId) as $ligne){
            $stock[$ligne->type] = (int) $ligne->nombre;
        }
        return $stock;
    }

    public static function stockLibre(int $planeteId, int $joueurId): array {
        $query = "SELECT type, COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_planete=? AND id_joueur=? AND arrivee IS NULL AND id_flotte IS NULL AND id_hote IS NULL GROUP BY type";
        $stock = array();
        foreach(MyPDO::getInstance()->query($query,$planeteId,$joueurId) as $ligne){
            $stock[$ligne->type] = (int) $ligne->nombre;
        }
        return $stock;
    }

    private static function conditionCombattants(?int $flotteId): array {
        if($flotteId === null){
            return array("id_flotte IS NULL AND id_hote IS NULL", array());
        }
        return array("(id_flotte=? OR id_hote IN (SELECT h.id FROM " . MyPDO::DB_FLAG . "vaisseau h WHERE h.id_flotte=?))", array($flotteId, $flotteId));
    }

    public static function combattants(int $planeteId, int $joueurId, ?int $flotteId): array {
        list($condition, $parametresCondition) = self::conditionCombattants($flotteId);
        $marques = implode(',', array_fill(0, count(Combat::ESCADRE), '?'));
        $query = "SELECT type, COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND id_planete=? AND arrivee IS NULL AND type IN ($marques) AND " . $condition . " GROUP BY type";
        $parametres = array_merge(array($joueurId, $planeteId), Combat::ESCADRE, $parametresCondition);
        $nombres = array_fill_keys(Combat::ESCADRE, 0);
        foreach(MyPDO::getInstance()->query($query,...$parametres) as $ligne){
            $nombres[$ligne->type] = (int) $ligne->nombre;
        }
        return $nombres;
    }

    public static function retirerCombattants(int $planeteId, int $joueurId, ?int $flotteId, array $pertes): void {
        list($condition, $parametresCondition) = self::conditionCombattants($flotteId);
        $db = MyPDO::getInstance();
        foreach($pertes as $type => $nombre){
            if($nombre < 1){
                continue;
            }
            $query = "SELECT id FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND id_planete=? AND type=? AND arrivee IS NULL AND " . $condition . " ORDER BY id LIMIT " . $nombre;
            $parametres = array_merge(array($joueurId, $planeteId, $type), $parametresCondition);
            $ids = array();
            foreach($db->query($query,...$parametres) as $ligne){
                array_push($ids, (int) $ligne->id);
            }
            if(count($ids) > 0){
                $db->query("DELETE FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id IN (" . implode(',', array_fill(0, count($ids), '?')) . ")",...$ids);
            }
        }
        Flotte::dissoudreVides($joueurId);
        self::libererOrphelins();
    }

    public static function escadre(int $planeteId, int $joueurId): array {
        $nombres = array_fill_keys(Combat::ESCADRE, 0);
        $marques = implode(',', array_fill(0, count(Combat::ESCADRE), '?'));
        $query = "SELECT type, COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_planete=? AND id_joueur=? AND arrivee IS NULL AND type IN ($marques) GROUP BY type";
        foreach(MyPDO::getInstance()->query($query,$planeteId,$joueurId,...Combat::ESCADRE) as $ligne){
            $nombres[$ligne->type] = (int) $ligne->nombre;
        }
        return $nombres;
    }

    public static function detruire(int $planeteId, int $joueurId, array $pertes): void {
        foreach($pertes as $type => $nombre){
            if($nombre < 1){
                continue;
            }
            $query = "DELETE FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND id_planete=? AND type=? AND arrivee IS NULL ORDER BY id LIMIT " . $nombre;
            MyPDO::getInstance()->query($query,$joueurId,$planeteId,$type);
        }
        Flotte::dissoudreVides($joueurId);
        self::libererOrphelins();
    }

    public static function nombreDeplacements(int $joueurId): int {
        $query = "SELECT COUNT(DISTINCT id_planete, id_destination, arrivee) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND arrivee IS NOT NULL";
        return (int) MyPDO::getInstance()->query($query,$joueurId)[0]->nombre;
    }

    public static function posees(int $joueurId): array {
        $query = "SELECT id_planete, type, COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND arrivee IS NULL AND id_flotte IS NULL AND id_hote IS NULL GROUP BY id_planete, type ORDER BY id_planete";
        $posees = array();
        foreach(MyPDO::getInstance()->query($query,$joueurId) as $ligne){
            $posees[$ligne->id_planete][$ligne->type] = (int) $ligne->nombre;
        }
        return $posees;
    }

    public static function enRoute(int $joueurId): array {
        $query = "SELECT id_planete, id_destination, type, depart, arrivee, exploration, COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND arrivee IS NOT NULL AND id_flotte IS NULL AND id_hote IS NULL GROUP BY id_planete, id_destination, type, depart, arrivee, exploration ORDER BY arrivee";
        return MyPDO::getInstance()->query($query,$joueurId);
    }

    public static function secondesDeBase(Planete $depart, Planete $destination): int {
        if($depart->id_systeme === $destination->id_systeme){
            return $depart->secondesVers($destination);
        }
        return $depart->systeme->distanceA($destination->systeme) * self::TRAJET_SECONDES_PAR_UNITE;
    }

    public static function dureeTrajet(Joueur $joueur, string $type, Planete $depart, Planete $destination): int {
        return self::dureeEntre($joueur, $type, self::secondesDeBase($depart, $destination), $depart->reductionTrajet($joueur));
    }

    public static function dureeEntre(Joueur $joueur, string $type, int $baseSecondes, int $reductionPourcent): int {
        return $joueur->dureeEffective(intdiv($baseSecondes * self::CATALOGUE[$type]['vitesse'] * (self::PERCENT - $reductionPourcent), self::PERCENT * self::PERCENT));
    }

    public static function dureeExpedition(Joueur $joueur): int {
        return $joueur->dureeEffective(intdiv(self::EXPLORATION_SECONDES * self::CATALOGUE[self::RECONNAISSANCE]['vitesse'], self::PERCENT));
    }

    public static function explorer(Joueur $joueur, Planete $depart, int $nombre): ?string {
        $disponibles = self::stock($depart->id, $joueur->id)[self::RECONNAISSANCE] ?? 0;
        if($nombre < 1 || $nombre > $disponibles){
            return 'Pas assez de vaisseaux : '.self::CATALOGUE[self::RECONNAISSANCE]['nom'].' (disponibles : '.$disponibles.').';
        }
        $db = MyPDO::getInstance();
        $query = "UPDATE " . MyPDO::DB_FLAG . "vaisseau SET id_destination=id_planete, depart=?, arrivee=?, exploration=1 WHERE id_joueur=? AND id_planete=? AND type=? AND arrivee IS NULL AND id_flotte IS NULL ORDER BY id LIMIT " . $nombre;
        $db->query($query,Tools::now(),Tools::nowPlus(self::dureeExpedition($joueur)),$joueur->id,$depart->id,self::RECONNAISSANCE);
        if($db->lastRowCount !== $nombre){
            return __('seul','seuls',$db->lastRowCount).' '.$db->lastRowCount.' '.__('vaisseau','vaisseaux',$db->lastRowCount).' '.__('disponible','disponibles',$db->lastRowCount).' : '.self::CATALOGUE[self::RECONNAISSANCE]['nom'].'.';
        }
        return null;
    }

    public static function resoudreExpeditions(Joueur $joueur): void {
        $db = MyPDO::getInstance();
        $perte = $joueur->enTuto() ? 0 : self::EXPLORATION_PERTE_POURCENT;
        $query = "SELECT id, id_planete FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND exploration=1 AND arrivee<=? ORDER BY id";
        foreach($db->query($query,$joueur->id,Tools::now()) as $ligne){
            if(random_int(1, self::PERCENT) <= $perte){
                $db->query("DELETE FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id=? AND exploration=1",$ligne->id);
                if($db->lastRowCount === 1){
                    Tools::addInfo('Signal perdu', 'Plus de nouvelles de votre vaisseau de reconnaissance : perdu à jamais.', self::ILLUSTRATION_PERDU);
                }
                continue;
            }
            $db->query("UPDATE " . MyPDO::DB_FLAG . "vaisseau SET exploration=0, id_destination=NULL, depart=NULL, arrivee=NULL WHERE id=? AND exploration=1",$ligne->id);
            if($db->lastRowCount !== 1){
                continue;
            }
            $planete = new Planete($ligne->id_planete);
            $systeme = self::decouverteDeLExpedition($joueur, $planete);
            if($systeme !== null){
                Tools::addInfo('Système découvert', $systeme->nom.' ('.count($systeme->planetes).' '.__('planete','planetes',count($systeme->planetes)).')', self::ILLUSTRATION_DECOUVERTE);
            }
            else{
                Tools::addInfo('Rien trouvé', 'Votre vaisseau de reconnaissance est revenu sur '.$planete->nom.' sans rien trouver.', self::ILLUSTRATION_RIEN);
            }
        }
    }

    private static function decouverteDeLExpedition(Joueur $joueur, Planete $depart): ?Systeme {
        if($joueur->enTuto() && $joueur->id_systeme_voisin !== null && !Decouverte::existe($joueur->id, $joueur->id_systeme_voisin)){
            Decouverte::ajouter($joueur->id, $joueur->id_systeme_voisin);
            return new Systeme($joueur->id_systeme_voisin);
        }
        if(random_int(1, self::PERCENT) <= self::EXPLORATION_DECOUVERTE_POURCENT){
            return Systeme::decouvrir($joueur, $depart->systeme);
        }
        return null;
    }

    public static function typesDeFlotte(): array {
        return array_merge(self::typesDeLUsine(Batiment::USINE_MOYENNE), self::typesDeLUsine(Batiment::USINE_LOURDE));
    }

    public static function ordonner(Joueur $joueur, Planete $depart, Planete $destination, array $nombres, array $flottes, array $vaisseaux): ?string {
        if($depart->id === $destination->id){
            return 'Les vaisseaux sont déjà sur cette planète.';
        }
        if(!$joueur->peutCibler($destination->systeme)){
            return 'Destination inconnue.';
        }
        $refusTutoriel = Tuto::verifierDestination($joueur, $destination);
        if($refusTutoriel !== null){
            return $refusTutoriel;
        }
        $nombres = array_filter($nombres, fn($nombre) => $nombre > 0);
        foreach(array_keys($nombres) as $type){
            if(!isset(self::CATALOGUE[$type])){
                return 'Type de vaisseau inconnu.';
            }
            if(self::estNomme($type)){
                return 'Choisissez ce vaisseau par son nom.';
            }
        }
        if(isset($nombres[self::TROUPE])){
            return 'Les troupes ne voyagent jamais seules : embarquez-les sur une barge, une frégate ou un vaisseau capital.';
        }
        if(isset($nombres[self::CHASSEUR]) && $depart->id_systeme !== $destination->id_systeme){
            return 'Les chasseurs ne changent de système qu\'embarqués : embarquez-les sur une frégate ou un vaisseau capital.';
        }
        foreach($flottes as $flotte){
            if($flotte->id_joueur !== $joueur->id || $flotte->planeteAuRepos() !== $depart->id){
                return $flotte->nom.' ne peut pas partir de cette planète.';
            }
        }
        $nommes = array();
        foreach($vaisseaux as $vaisseauId){
            $vaisseau = self::auRepos($joueur, (int) $vaisseauId);
            if($vaisseau === null || $vaisseau->id_planete !== $depart->id || $vaisseau->id_flotte !== null || !self::estNomme($vaisseau->type)){
                return 'Ce vaisseau ne peut pas partir de cette planète.';
            }
            array_push($nommes, $vaisseau);
        }
        foreach($nombres as $type => $nombre){
            $erreur = self::envoyerType($joueur, $depart, $destination, $type, $nombre);
            if($erreur !== null){
                return $erreur;
            }
        }
        foreach($nommes as $vaisseau){
            $erreur = self::envoyerNomme($joueur, $depart, $destination, $vaisseau);
            if($erreur !== null){
                return $erreur;
            }
        }
        foreach($flottes as $flotte){
            $erreur = $flotte->deplacer($joueur, $destination);
            if($erreur !== null){
                return $erreur;
            }
        }
        return null;
    }

    public static function troupesEnBarge(int $planeteId, int $joueurId): int {
        return (int) MyPDO::getInstance()->query("SELECT COUNT(*) AS nombre" . self::TROUPES_EN_BARGE,$joueurId,$planeteId,self::TROUPE,self::BARGE)[0]->nombre;
    }

    public static function fuir(Joueur $joueur, Planete $depart, Planete $refuge, ?int $flotteId): void {
        if($flotteId !== null){
            (new Flotte($flotteId))->deplacer($joueur, $refuge);
            return;
        }
        $marques = implode(',', array_fill(0, count(Combat::ESCADRE), '?'));
        $query = "SELECT id, type, nom FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND id_planete=? AND arrivee IS NULL AND id_flotte IS NULL AND id_hote IS NULL AND type IN ($marques)";
        $parametres = array_merge(array($joueur->id, $depart->id), Combat::ESCADRE);
        $chasseurs = 0;
        foreach(MyPDO::getInstance()->query($query,...$parametres) as $ligne){
            if(self::estNomme($ligne->type)){
                $ligne->id = (int) $ligne->id;
                self::envoyerNomme($joueur, $depart, $refuge, $ligne);
            }
            else{
                $chasseurs++;
            }
        }
        if($chasseurs > 0){
            self::envoyerType($joueur, $depart, $refuge, self::CHASSEUR, $chasseurs);
        }
    }

    public static function fuirTous(Joueur $joueur, Planete $depart, Planete $refuge): void {
        foreach(Flotte::liste($joueur->id) as $ligne){
            $flotte = new Flotte($ligne->id);
            if($flotte->planeteAuRepos() === $depart->id){
                $flotte->deplacer($joueur, $refuge);
            }
        }
        self::fuir($joueur, $depart, $refuge, null);
    }

    public static function estNomme(string $type): bool {
        return isset(self::CATALOGUE[$type][self::CLE_PREFIXE]);
    }

    private static function auRepos(Joueur $joueur, int $vaisseauId): ?object {
        $query = "SELECT * FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id=? AND id_joueur=? AND arrivee IS NULL";
        $lignes = MyPDO::getInstance()->query($query,$vaisseauId,$joueur->id);
        if(count($lignes) === 0){
            return null;
        }
        $lignes[0]->id = (int) $lignes[0]->id;
        $lignes[0]->id_planete = (int) $lignes[0]->id_planete;
        $lignes[0]->id_flotte = $lignes[0]->id_flotte === null ? null : (int) $lignes[0]->id_flotte;
        return $lignes[0];
    }

    private static function envoyerNomme(Joueur $joueur, Planete $depart, Planete $destination, object $vaisseau): ?string {
        $db = MyPDO::getInstance();
        $duree = self::dureeTrajet($joueur, $vaisseau->type, $depart, $destination);
        $heureDepart = Tools::now();
        $arrivee = Tools::nowPlus($duree);
        $query = "UPDATE " . MyPDO::DB_FLAG . "vaisseau SET id_destination=?, depart=?, arrivee=? WHERE id=? AND arrivee IS NULL AND id_flotte IS NULL";
        $db->query($query,$destination->id,$heureDepart,$arrivee,$vaisseau->id);
        if($db->lastRowCount !== 1){
            return $vaisseau->nom.' est déjà en déplacement.';
        }
        $query = "UPDATE " . MyPDO::DB_FLAG . "vaisseau SET id_destination=?, depart=?, arrivee=? WHERE id_hote=? AND arrivee IS NULL";
        $db->query($query,$destination->id,$heureDepart,$arrivee,$vaisseau->id);
        Tools::addMessage($vaisseau->nom.' : départ vers '.$destination->nom.', arrivée dans '.gmdate('i:s', $duree).'.');
        return null;
    }

    public static function embarques(int $hoteId): array {
        $query = "SELECT type, COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_hote=? GROUP BY type";
        $embarques = array();
        foreach(MyPDO::getInstance()->query($query,$hoteId) as $ligne){
            $embarques[$ligne->type] = (int) $ligne->nombre;
        }
        return $embarques;
    }

    public static function embarquer(Joueur $joueur, int $hoteId, string $type, int $nombre): ?string {
        $hote = self::auRepos($joueur, $hoteId);
        if($hote === null || !self::estNomme($hote->type)){
            return 'Ce vaisseau ne peut pas embarquer maintenant.';
        }
        if($type === self::TROUPE){
            $cle = self::CAPACITE_TROUPES;
        }
        elseif($type === self::CHASSEUR){
            $cle = self::CAPACITE_CHASSEURS;
        }
        else{
            return 'Seuls les troupes et les chasseurs s\'embarquent.';
        }
        $deja = self::embarques($hote->id)[$type] ?? 0;
        $libres = self::stockLibre($hote->id_planete, $joueur->id)[$type] ?? 0;
        $place = max(0, min(self::CATALOGUE[$hote->type][$cle] - $deja, $libres));
        if($nombre < 1 || $nombre > $place){
            $mot = $type === self::TROUPE ? __('troupe','troupes',$place) : __('chasseur','chasseurs',$place);
            return $hote->nom.' peut embarquer au plus '.$place.' '.$mot.'.';
        }
        $db = MyPDO::getInstance();
        $query = "UPDATE " . MyPDO::DB_FLAG . "vaisseau SET id_hote=? WHERE id_joueur=? AND id_planete=? AND type=? AND arrivee IS NULL AND id_flotte IS NULL AND id_hote IS NULL ORDER BY id LIMIT " . $nombre;
        $db->query($query,$hote->id,$joueur->id,$hote->id_planete,$type);
        $embarques = $db->lastRowCount;
        if(self::auRepos($joueur, $hote->id) === null){
            $db->query("UPDATE " . MyPDO::DB_FLAG . "vaisseau SET id_hote=NULL WHERE id_hote=? AND arrivee IS NULL",$hote->id);
            return 'La situation a changé sur cette planète : recommencez.';
        }
        if($embarques !== $nombre){
            return 'La situation a changé sur cette planète : recommencez.';
        }
        return null;
    }

    public static function debarquer(Joueur $joueur, int $hoteId): ?string {
        $hote = self::auRepos($joueur, $hoteId);
        if($hote === null || !self::estNomme($hote->type)){
            return 'Ce vaisseau ne peut pas débarquer maintenant.';
        }
        MyPDO::getInstance()->query("UPDATE " . MyPDO::DB_FLAG . "vaisseau SET id_hote=NULL WHERE id_hote=?",$hote->id);
        return null;
    }

    public static function renommer(Joueur $joueur, int $vaisseauId, string $nom): ?string {
        $nom = trim($nom);
        if($nom === '' || mb_strlen($nom) > self::NOM_LONGUEUR_MAX){
            return 'Le nom doit faire entre 1 et '.self::NOM_LONGUEUR_MAX.' caractères.';
        }
        $query = "UPDATE " . MyPDO::DB_FLAG . "vaisseau SET nom=? WHERE id=? AND id_joueur=? AND nom IS NOT NULL";
        MyPDO::getInstance()->query($query,$nom,$vaisseauId,$joueur->id);
        return null;
    }

    public static function libererOrphelins(): void {
        $query = "UPDATE " . MyPDO::DB_FLAG . "vaisseau e LEFT JOIN " . MyPDO::DB_FLAG . "vaisseau h ON h.id=e.id_hote SET e.id_hote=NULL WHERE e.id_hote IS NOT NULL AND h.id IS NULL";
        MyPDO::getInstance()->query($query);
    }

    public static function nommesAuRepos(int $joueurId): array {
        $marques = implode(',', array_fill(0, count(self::typesNommes()), '?'));
        $query = "SELECT id, id_planete, type, nom FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND arrivee IS NULL AND id_flotte IS NULL AND type IN ($marques) ORDER BY id";
        $parPlanete = array();
        foreach(MyPDO::getInstance()->query($query,$joueurId,...self::typesNommes()) as $ligne){
            $parPlanete[$ligne->id_planete][] = $ligne;
        }
        return $parPlanete;
    }

    public static function typesNommes(): array {
        return array_keys(array_filter(self::CATALOGUE, fn($infos) => isset($infos[self::CLE_PREFIXE])));
    }

    private static function envoyerType(Joueur $joueur, Planete $depart, Planete $destination, string $type, int $nombre): ?string {
        $disponibles = self::stockLibre($depart->id, $joueur->id)[$type] ?? 0;
        if($nombre > $disponibles){
            return 'Pas assez de vaisseaux : '.self::CATALOGUE[$type]['nom'].' (disponibles : '.$disponibles.').';
        }
        $db = MyPDO::getInstance();
        $duree = self::dureeTrajet($joueur, $type, $depart, $destination);
        $arrivee = Tools::nowPlus($duree);
        $query = "UPDATE " . MyPDO::DB_FLAG . "vaisseau SET id_destination=?, depart=?, arrivee=? WHERE id_joueur=? AND id_planete=? AND type=? AND arrivee IS NULL AND id_flotte IS NULL AND id_hote IS NULL ORDER BY id LIMIT " . $nombre;
        $db->query($query,$destination->id,Tools::now(),$arrivee,$joueur->id,$depart->id,$type);
        if($db->lastRowCount !== $nombre){
            return __('seul','seuls',$db->lastRowCount).' '.$db->lastRowCount.' '.__('vaisseau','vaisseaux',$db->lastRowCount).' '.__('disponible','disponibles',$db->lastRowCount).' : '.self::CATALOGUE[$type]['nom'].'.';
        }
        Tools::addMessage($nombre.' × '.self::CATALOGUE[$type]['nom'].' : départ vers '.$destination->nom.', arrivée dans '.gmdate('i:s', $duree).'.');
        if($type === self::TRANSPORT_PERSO){
            $passagers = Perso::emmener($joueur, $depart, $destination, $nombre * self::CAPACITE_TRANSPORT_PERSO, $arrivee);
            if($passagers > 0){
                Tools::addMessage($passagers.' '.__('perso','persos',$passagers).' '.__('voyage','voyagent',$passagers).' à bord.');
            }
        }
        return null;
    }

    public static function resolveArrivees(Joueur $joueur): void {
        $db = MyPDO::getInstance();
        $planetesArmees = array();
        $query = "SELECT id_destination, type FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND exploration=0 AND arrivee IS NOT NULL AND arrivee<=? GROUP BY id_destination, type";
        foreach($db->query($query,$joueur->id,Tools::now()) as $ligne){
            $query = "UPDATE " . MyPDO::DB_FLAG . "vaisseau SET id_planete=id_destination, id_destination=NULL, depart=NULL, arrivee=NULL WHERE id_joueur=? AND id_destination=? AND type=? AND exploration=0 AND arrivee IS NOT NULL AND arrivee<=?";
            $db->query($query,$joueur->id,$ligne->id_destination,$ligne->type,Tools::now());
            $nombre = $db->lastRowCount;
            if($nombre > 0){
                $planete = new Planete($ligne->id_destination);
                Tools::addMessage($nombre.' × '.self::CATALOGUE[$ligne->type]['nom'].' : arrivée sur '.$planete->nom.'.');
                if(in_array($ligne->type, Combat::ESCADRE, true)){
                    $planetesArmees[$planete->id] = $planete;
                }
                if($ligne->type === self::RECONNAISSANCE){
                    Analyse::enregistrer($joueur, $planete);
                    $analyse = $planete->nom.' : fiche complète relevée.';
                    if(Decouverte::ajouter($joueur->id, $planete->id_systeme)){
                        $analyse = $planete->nom.' : fiche complète relevée, système '.$planete->systeme->nom.' exploré, '.count($planete->systeme->planetes).' '.__('planete','planetes',count($planete->systeme->planetes)).' '.__('decouverte','decouvertes',count($planete->systeme->planetes)).'.';
                    }
                    Tools::addInfo('Planète analysée', $analyse, self::ILLUSTRATION_ANALYSE);
                }
            }
        }
        foreach($planetesArmees as $planete){
            Combat::arrivee($joueur, new Planete($planete->id));
        }
    }

    public static function produire(Joueur $joueur, Planete $planete, string $type, int $nombre): ?string {
        if(!isset(self::CATALOGUE[$type])){
            return 'Type de vaisseau inconnu.';
        }
        if($planete->gouverneur !== $joueur->id){
            return 'Vous ne gouvernez pas cette planète.';
        }
        if($nombre < 1){
            return 'Choisissez au moins une unité.';
        }
        $infos = self::CATALOGUE[$type];
        $usine = $infos['usine'];
        if($planete->nombre($usine) < 1){
            return "Cette planète n'a pas l'usine nécessaire.";
        }
        if($type === self::TROUPE){
            $place = self::placeTroupes($planete, $joueur->id);
            if($nombre > $place){
                return "L'académie ne permet plus que ".$place.' '.__('troupe','troupes',$place).' sur cette planète.';
            }
        }
        if(!$joueur->payer($nombre * $joueur->prixVaisseau($type))){
            return 'Crédits insuffisants.';
        }
        foreach(self::finsDeFile($planete, $usine, $nombre, $planete->dureeProduction($joueur, $infos['duree'])) as $fin){
            MyPDO::getInstance()->query("INSERT INTO " . MyPDO::DB_FLAG . "production (id_joueur,id_planete,type,fin) VALUES (?,?,?,?)",$joueur->id,$planete->id,$type,$fin);
        }
        return null;
    }

    public static function enFile(int $planeteId): array {
        $file = array();
        foreach(self::enProduction($planeteId) as $ligne){
            if(!isset($file[$ligne->type])){
                $file[$ligne->type] = array('type' => $ligne->type, 'restants' => 0, 'fin' => $ligne->fin);
            }
            $file[$ligne->type]['restants']++;
        }
        return array_values($file);
    }

    public static function placeTroupes(Planete $planete, int $joueurId): int {
        return max(0, Batiment::capaciteTroupes($planete) - self::troupesSurPlanete($planete->id, $joueurId));
    }

    private static function troupesSurPlanete(int $planeteId, int $joueurId): int {
        $query = "SELECT COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND id_planete=? AND type=? AND arrivee IS NULL";
        $presentes = (int) MyPDO::getInstance()->query($query,$joueurId,$planeteId,self::TROUPE)[0]->nombre;
        $query = "SELECT COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "production WHERE id_joueur=? AND id_planete=? AND type=?";
        return $presentes + (int) MyPDO::getInstance()->query($query,$joueurId,$planeteId,self::TROUPE)[0]->nombre;
    }

    private static function finsDeFile(Planete $planete, string $usine, int $nombre, int $duree): array {
        $types = self::typesDeLUsine($usine);
        $marques = implode(',', array_fill(0, count($types), '?'));
        $query = "SELECT fin FROM " . MyPDO::DB_FLAG . "production WHERE id_planete=? AND type IN ($marques) ORDER BY fin";
        $voies = array_fill(0, $planete->nombre($usine), time());
        foreach(MyPDO::getInstance()->query($query,$planete->id,...$types) as $ligne){
            sort($voies);
            $voies[0] = max($voies[0], strtotime($ligne->fin));
        }
        $fins = array();
        for($i = 0; $i < $nombre; $i++){
            sort($voies);
            $voies[0] += $duree;
            array_push($fins, Tools::formatDate($voies[0]));
        }
        return $fins;
    }

    public static function resolveProduction(Joueur $joueur): void {
        $db = MyPDO::getInstance();
        $query = "SELECT * FROM " . MyPDO::DB_FLAG . "production WHERE id_joueur=? AND fin<=?";
        $prets = array();
        foreach($db->query($query,$joueur->id,Tools::now()) as $ligne){
            $db->query("DELETE FROM " . MyPDO::DB_FLAG . "production WHERE id=?",$ligne->id);
            if($db->lastRowCount !== 1){
                continue;
            }
            $numero = null;
            $nom = null;
            if(self::estNomme($ligne->type)){
                $query = "SELECT COALESCE(MAX(numero), 0) + 1 AS suivant FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND type=?";
                $numero = (int) $db->query($query,$joueur->id,$ligne->type)[0]->suivant;
                $nom = self::CATALOGUE[$ligne->type][self::CLE_PREFIXE].' '.$numero;
            }
            $db->query("INSERT INTO " . MyPDO::DB_FLAG . "vaisseau (id_joueur,id_planete,type,numero,nom,creato) VALUES (?,?,?,?,?,?)",$joueur->id,$ligne->id_planete,$ligne->type,$numero,$nom,Tools::now());
            $prets[$ligne->id_planete][$ligne->type] = ($prets[$ligne->id_planete][$ligne->type] ?? 0) + 1;
        }
        foreach($prets as $planeteId => $types){
            $planete = new Planete($planeteId);
            foreach($types as $type => $nombre){
                Tools::addMessage($nombre.' × '.self::CATALOGUE[$type]['nom'].' prêt sur '.$planete->nom.'.');
            }
        }
    }

}
