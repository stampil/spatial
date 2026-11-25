<?php

class Vaisseau {

    const TRAJET_MEME_SYSTEME_SECONDES = 60;
    const TRAJET_SECONDES_PAR_UNITE = 1;
    const PERCENT = 100;
    const RECONNAISSANCE = 'reconnaissance';
    const BARGE = 'barge';
    const TROUPE = 'troupe_assaut';
    const CAPACITE_BARGE = 10;
    const DESTINATION_EXPLORATION = 'explorer';
    const TRANSPORT_PERSO = 'transport_perso';
    const CAPACITE_TRANSPORT_PERSO = 2;

    const CATALOGUE = array(
        'chasseur' => array('icone' => 'chasseur', 'nom' => 'Chasseur', 'usine' => Batiment::USINE_LEGERE, 'prix' => 40, 'duree' => 30, 'vitesse' => 100, 'force' => 5),
        'reconnaissance' => array('icone' => 'reconnaissance', 'nom' => 'Vaisseau de reconnaissance', 'usine' => Batiment::USINE_LEGERE, 'prix' => 30, 'duree' => 30, 'vitesse' => 50, 'force' => 0),
        'transport_perso' => array('icone' => 'transport', 'nom' => 'Transport personnel', 'usine' => Batiment::USINE_LEGERE, 'prix' => 50, 'duree' => 45, 'vitesse' => 60, 'force' => 0),
        'fregate' => array('icone' => 'fregate', 'nom' => 'Frégate', 'usine' => Batiment::USINE_MOYENNE, 'prix' => 120, 'duree' => 90, 'vitesse' => 100, 'force' => 15),
        'barge' => array('icone' => 'barge', 'nom' => 'Barge de débarquement', 'usine' => Batiment::USINE_MOYENNE, 'prix' => 150, 'duree' => 120, 'vitesse' => 200, 'force' => 0),
        'capital' => array('icone' => 'fusee', 'nom' => 'Vaisseau capital', 'usine' => Batiment::USINE_LOURDE, 'prix' => 400, 'duree' => 300, 'vitesse' => 150, 'force' => 50),
        'troupe_assaut' => array('icone' => 'troupe', 'nom' => 'Troupe d\'assaut', 'usine' => Batiment::ACADEMIE, 'prix' => 60, 'duree' => 20, 'vitesse' => 200, 'force' => 10),
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
    }

    public static function nombreDeplacements(int $joueurId): int {
        $query = "SELECT COUNT(DISTINCT id_planete, id_destination, arrivee) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND arrivee IS NOT NULL";
        return (int) MyPDO::getInstance()->query($query,$joueurId)[0]->nombre;
    }

    public static function posees(int $joueurId): array {
        $query = "SELECT id_planete, type, COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND arrivee IS NULL AND id_flotte IS NULL GROUP BY id_planete, type ORDER BY id_planete";
        $posees = array();
        foreach(MyPDO::getInstance()->query($query,$joueurId) as $ligne){
            $posees[$ligne->id_planete][$ligne->type] = (int) $ligne->nombre;
        }
        return $posees;
    }

    public static function enRoute(int $joueurId): array {
        $query = "SELECT id_planete, id_destination, type, depart, arrivee, COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND arrivee IS NOT NULL AND id_flotte IS NULL GROUP BY id_planete, id_destination, type, depart, arrivee ORDER BY arrivee";
        return MyPDO::getInstance()->query($query,$joueurId);
    }

    public static function dureeTrajet(Joueur $joueur, string $type, Planete $depart, Planete $destination): int {
        return self::dureeEntre($joueur, $type, $depart->systeme->distanceA($destination->systeme), $depart->reductionTrajet($joueur));
    }

    public static function dureeEntre(Joueur $joueur, string $type, int $distance, int $reductionPourcent): int {
        $base = $distance * self::TRAJET_SECONDES_PAR_UNITE;
        if($distance === Systeme::DISTANCE_MEME_SYSTEME){
            $base = self::TRAJET_MEME_SYSTEME_SECONDES;
        }
        return $joueur->dureeEffective(intdiv($base * self::CATALOGUE[$type]['vitesse'] * (self::PERCENT - $reductionPourcent), self::PERCENT * self::PERCENT));
    }

    public static function cibleExploration(Joueur $joueur): ?Planete {
        $cibles = Systeme::explorables($joueur->id);
        if(count($cibles) === 0){
            return null;
        }
        $cible = $cibles[random_int(0, count($cibles) - 1)];
        return new Planete($cible->planetes[0]->id);
    }

    public static function deplacer(Joueur $joueur, Planete $depart, Planete $destination, string $type, int $nombre, bool $exploration = false): ?string {
        if(!isset(self::CATALOGUE[$type])){
            return 'Type de vaisseau inconnu.';
        }
        if($depart->id === $destination->id){
            return 'Les vaisseaux sont déjà sur cette planète.';
        }
        if(!$exploration && !$joueur->peutCibler($destination->systeme)){
            return 'Destination inconnue.';
        }
        $disponibles = self::stock($depart->id, $joueur->id)[$type] ?? 0;
        if($nombre < 1 || $nombre > $disponibles){
            return 'Pas assez de vaisseaux : '.self::CATALOGUE[$type]['nom'].' (disponibles : '.$disponibles.').';
        }
        $db = MyPDO::getInstance();
        $arrivee = Tools::nowPlus(self::dureeTrajet($joueur, $type, $depart, $destination));
        $query = "UPDATE " . MyPDO::DB_FLAG . "vaisseau SET id_destination=?, depart=?, arrivee=? WHERE id_joueur=? AND id_planete=? AND type=? AND arrivee IS NULL AND id_flotte IS NULL ORDER BY id LIMIT " . $nombre;
        $db->query($query,$destination->id,Tools::now(),$arrivee,$joueur->id,$depart->id,$type);
        if($db->lastRowCount !== $nombre){
            return 'Seuls '.$db->lastRowCount.' vaisseau(x) étaient disponibles : '.self::CATALOGUE[$type]['nom'].'.';
        }
        if($type === self::TRANSPORT_PERSO){
            $passagers = Perso::emmener($joueur, $depart, $destination, $nombre * self::CAPACITE_TRANSPORT_PERSO, $arrivee);
            if($passagers > 0){
                Tools::addMessage($passagers.' perso(s) voyagent à bord.');
            }
        }
        return null;
    }

    public static function resolveArrivees(Joueur $joueur): void {
        $db = MyPDO::getInstance();
        $query = "SELECT id_destination, type FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND arrivee IS NOT NULL AND arrivee<=? GROUP BY id_destination, type";
        foreach($db->query($query,$joueur->id,Tools::now()) as $ligne){
            $query = "UPDATE " . MyPDO::DB_FLAG . "vaisseau SET id_planete=id_destination, id_destination=NULL, depart=NULL, arrivee=NULL WHERE id_joueur=? AND id_destination=? AND type=? AND arrivee IS NOT NULL AND arrivee<=?";
            $db->query($query,$joueur->id,$ligne->id_destination,$ligne->type,Tools::now());
            $nombre = $db->lastRowCount;
            if($nombre > 0){
                $planete = new Planete($ligne->id_destination);
                Tools::addMessage($nombre.' × '.self::CATALOGUE[$ligne->type]['nom'].' : arrivée sur '.$planete->nom.'.');
                if($ligne->type === self::RECONNAISSANCE && Decouverte::ajouter($joueur->id, $planete->id_systeme)){
                    Tools::addMessage('Système '.$planete->systeme->nom.' exploré : '.count($planete->systeme->planetes).' planètes découvertes.');
                }
            }
        }
    }

    public static function produire(Joueur $joueur, Planete $planete, string $type): ?string {
        if(!isset(self::CATALOGUE[$type])){
            return 'Type de vaisseau inconnu.';
        }
        if($planete->gouverneur !== $joueur->id){
            return 'Vous ne gouvernez pas cette planète.';
        }
        $infos = self::CATALOGUE[$type];
        $usine = $infos['usine'];
        if($planete->nombre($usine) < 1){
            return "Cette planète n'a pas l'usine nécessaire.";
        }
        $types = self::typesDeLUsine($usine);
        $marques = implode(',', array_fill(0, count($types), '?'));
        $db = MyPDO::getInstance();
        $query = "INSERT INTO " . MyPDO::DB_FLAG . "production (id_joueur,id_planete,type,fin)
            SELECT ?,?,?,? FROM " . MyPDO::DB_FLAG . "planete
            WHERE id=? AND gouverneur=? AND $usine > (SELECT COUNT(*) FROM " . MyPDO::DB_FLAG . "production WHERE id_planete=? AND type IN ($marques))";
        $db->query($query,$joueur->id,$planete->id,$type,Tools::nowPlus($joueur->dureeEffective($infos['duree'])),$planete->id,$joueur->id,$planete->id,...$types);
        if($db->lastRowCount !== 1){
            return 'Toutes les usines de ce type sont occupées.';
        }
        $reservation = (int) $db->lastInsertId();
        if(!$joueur->payer($joueur->prixVaisseau($type))){
            $db->query("DELETE FROM " . MyPDO::DB_FLAG . "production WHERE id=?",$reservation);
            return 'Crédits insuffisants.';
        }
        return null;
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
            $db->query("INSERT INTO " . MyPDO::DB_FLAG . "vaisseau (id_joueur,id_planete,type,creato) VALUES (?,?,?,?)",$joueur->id,$ligne->id_planete,$ligne->type,Tools::now());
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
