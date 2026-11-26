<?php

class Vaisseau {

    const TRAJET_MEME_SYSTEME_SECONDES = 60;
    const TRAJET_SECONDES_PAR_UNITE = 1;
    const PERCENT = 100;
    const RECONNAISSANCE = 'reconnaissance';
    const CHASSEUR = 'chasseur';
    const BARGE = 'barge';
    const TROUPE = 'troupe_assaut';
    const DESTINATION_EXPLORATION = 'explorer';
    const EXPLORATION_SECONDES = 900;
    const EXPLORATION_PERTE_POURCENT = 20;
    const EXPLORATION_DECOUVERTE_POURCENT = 50;
    const TRANSPORT_PERSO = 'transport_perso';
    const CAPACITE_TRANSPORT_PERSO = 2;

    const CATALOGUE = array(
        'chasseur' => array('icone' => 'chasseur', 'nom' => 'Chasseur', 'usine' => Batiment::USINE_LEGERE, 'prix' => 40, 'duree' => 30, 'vitesse' => 100, 'force' => 5, 'troupes' => 0, 'chasseurs' => 0),
        'reconnaissance' => array('icone' => 'reconnaissance', 'nom' => 'Vaisseau de reconnaissance', 'usine' => Batiment::USINE_LEGERE, 'prix' => 30, 'duree' => 30, 'vitesse' => 50, 'force' => 0, 'troupes' => 0, 'chasseurs' => 0),
        'transport_perso' => array('icone' => 'transport', 'nom' => 'Transport personnel', 'usine' => Batiment::USINE_LEGERE, 'prix' => 50, 'duree' => 45, 'vitesse' => 60, 'force' => 0, 'troupes' => 0, 'chasseurs' => 0),
        'fregate' => array('icone' => 'fregate', 'nom' => 'Frégate', 'usine' => Batiment::USINE_MOYENNE, 'prix' => 120, 'duree' => 90, 'vitesse' => 100, 'force' => 15, 'troupes' => 10, 'chasseurs' => 3),
        'barge' => array('icone' => 'barge', 'nom' => 'Barge de débarquement', 'usine' => Batiment::USINE_MOYENNE, 'prix' => 150, 'duree' => 120, 'vitesse' => 200, 'force' => 0, 'troupes' => 10, 'chasseurs' => 0),
        'capital' => array('icone' => 'fusee', 'nom' => 'Vaisseau capital', 'usine' => Batiment::USINE_LOURDE, 'prix' => 400, 'duree' => 300, 'vitesse' => 150, 'force' => 50, 'troupes' => 20, 'chasseurs' => 6),
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
        $query = "SELECT id_planete, id_destination, type, depart, arrivee, exploration, COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND arrivee IS NOT NULL AND id_flotte IS NULL GROUP BY id_planete, id_destination, type, depart, arrivee, exploration ORDER BY arrivee";
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
                    Tools::addMessage('Un vaisseau de reconnaissance est parti explorer l\'inconnu et n\'est jamais revenu.');
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
                Tools::addMessage('Un vaisseau de reconnaissance est revenu sur '.$planete->nom.' : système '.$systeme->nom.' découvert ('.count($systeme->planetes).' '.__('planete','planetes',count($systeme->planetes)).').');
            }
            else{
                Tools::addMessage('Un vaisseau de reconnaissance est revenu sur '.$planete->nom.' sans rien trouver.');
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

    public static function capacite(array $nombres, string $cle): int {
        $total = 0;
        foreach($nombres as $type => $nombre){
            $total += $nombre * self::CATALOGUE[$type][$cle];
        }
        return $total;
    }

    public static function ordonner(Joueur $joueur, Planete $depart, Planete $destination, array $nombres, array $flottes): ?string {
        if($depart->id === $destination->id){
            return 'Les vaisseaux sont déjà sur cette planète.';
        }
        if(!$joueur->peutCibler($destination->systeme)){
            return 'Destination inconnue.';
        }
        $nombres = array_filter($nombres, fn($nombre) => $nombre > 0);
        foreach(array_keys($nombres) as $type){
            if(!isset(self::CATALOGUE[$type])){
                return 'Type de vaisseau inconnu.';
            }
        }
        $convoi = array_diff_key($nombres, array(self::TROUPE => 0, self::CHASSEUR => 0));
        foreach($flottes as $flotte){
            if($flotte->id_joueur !== $joueur->id || $flotte->planeteAuRepos() !== $depart->id){
                return 'La flotte '.$flotte->nom.' ne peut pas partir de cette planète.';
            }
            foreach($flotte->composition() as $type => $nombre){
                $convoi[$type] = ($convoi[$type] ?? 0) + $nombre;
            }
        }
        $memeSysteme = $depart->id_systeme === $destination->id_systeme;
        $troupes = $nombres[self::TROUPE] ?? 0;
        if($troupes > self::capacite($convoi, 'troupes')){
            return 'Les troupes ne voyagent jamais seules : '.$troupes.' '.__('troupe','troupes',$troupes).' '.__('demande','demandent',$troupes).' des vaisseaux capables de les embarquer (barge, frégate ou vaisseau capital).';
        }
        $chasseurs = $nombres[self::CHASSEUR] ?? 0;
        if(!$memeSysteme && $chasseurs > self::capacite($convoi, 'chasseurs')){
            return 'Les chasseurs ne changent de système que embarqués : '.$chasseurs.' '.__('chasseur','chasseurs',$chasseurs).' '.__('demande','demandent',$chasseurs).' un vaisseau capable de les emporter (frégate ou vaisseau capital).';
        }
        $dureeConvoi = null;
        foreach(array_keys($convoi) as $type){
            $dureeConvoi = max($dureeConvoi ?? 0, self::dureeTrajet($joueur, $type, $depart, $destination));
        }
        foreach($nombres as $type => $nombre){
            $embarque = $type === self::TROUPE || ($type === self::CHASSEUR && !$memeSysteme);
            $erreur = self::envoyerType($joueur, $depart, $destination, $type, $nombre, $embarque ? $dureeConvoi : null);
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

    private static function envoyerType(Joueur $joueur, Planete $depart, Planete $destination, string $type, int $nombre, ?int $duree): ?string {
        $disponibles = self::stock($depart->id, $joueur->id)[$type] ?? 0;
        if($nombre > $disponibles){
            return 'Pas assez de vaisseaux : '.self::CATALOGUE[$type]['nom'].' (disponibles : '.$disponibles.').';
        }
        $db = MyPDO::getInstance();
        $duree = $duree ?? self::dureeTrajet($joueur, $type, $depart, $destination);
        $arrivee = Tools::nowPlus($duree);
        $query = "UPDATE " . MyPDO::DB_FLAG . "vaisseau SET id_destination=?, depart=?, arrivee=? WHERE id_joueur=? AND id_planete=? AND type=? AND arrivee IS NULL AND id_flotte IS NULL ORDER BY id LIMIT " . $nombre;
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
        $query = "SELECT id_destination, type FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND exploration=0 AND arrivee IS NOT NULL AND arrivee<=? GROUP BY id_destination, type";
        foreach($db->query($query,$joueur->id,Tools::now()) as $ligne){
            $query = "UPDATE " . MyPDO::DB_FLAG . "vaisseau SET id_planete=id_destination, id_destination=NULL, depart=NULL, arrivee=NULL WHERE id_joueur=? AND id_destination=? AND type=? AND exploration=0 AND arrivee IS NOT NULL AND arrivee<=?";
            $db->query($query,$joueur->id,$ligne->id_destination,$ligne->type,Tools::now());
            $nombre = $db->lastRowCount;
            if($nombre > 0){
                $planete = new Planete($ligne->id_destination);
                Tools::addMessage($nombre.' × '.self::CATALOGUE[$ligne->type]['nom'].' : arrivée sur '.$planete->nom.'.');
                if($ligne->type === self::RECONNAISSANCE && Decouverte::ajouter($joueur->id, $planete->id_systeme)){
                    Tools::addMessage('Système '.$planete->systeme->nom.' exploré : '.count($planete->systeme->planetes).' '.__('planete','planetes',count($planete->systeme->planetes)).' '.__('decouverte','decouvertes',count($planete->systeme->planetes)).'.');
                }
            }
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
        foreach(self::finsDeFile($planete, $usine, $nombre, $joueur->dureeEffective($infos['duree'])) as $fin){
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
