<?php

class Carte {

    const STATUT_MIEN = 'Votre colonie';
    const STATUT_LIBRE = 'Libre';
    const STATUT_OCCUPE = 'Occupée';

    public static function donnees(Joueur $joueur): array {
        $camps = array();
        foreach(Camp::liste() as $camp){
            $camps[$camp->id] = $camp;
        }
        $systemes = array();
        foreach(Systeme::listeVisibles($joueur->id) as $systeme){
            $systemes[$systeme->id] = $systeme;
        }

        $analysees = Analyse::planetesAnalysees($joueur->id);
        $enRoute = self::enRoute($joueur, $systemes);
        $donnees = array(
            'maintenant' => time(),
            'base' => self::systemeDeBase($joueur),
            'systemes' => array(),
            'stocks' => self::stocks($joueur, $systemes, $systemes),
            'flottes' => self::flottes($joueur, $systemes, $systemes),
            'enRoute' => $enRoute,
            'enRouteLibelle' => count($enRoute).' '.__('deplacement','deplacements',count($enRoute)),
        );
        foreach($systemes as $systeme){
            array_push($donnees['systemes'], self::systeme($joueur, $systeme, $camps, $analysees));
        }
        return $donnees;
    }

    public static function ordreDeLaRequete(Joueur $joueur, array $requete): ?array {
        if(!isset($requete['ordre'])){
            return null;
        }
        $ordre = array('planete' => (int) $requete['ordre'], 'nombres' => array(), 'flottes' => array(), 'vaisseau' => null, 'libelle' => '');
        if(isset($requete['vaisseau'])){
            $query = "SELECT id, type, nom FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id=? AND id_joueur=? AND nom IS NOT NULL";
            $lignes = MyPDO::getInstance()->query($query,(int) $requete['vaisseau'],$joueur->id);
            if(count($lignes) === 0){
                return null;
            }
            $ordre['vaisseau'] = (int) $lignes[0]->id;
            $ordre['nombres'][$lignes[0]->type] = 1;
            $ordre['libelle'] = $lignes[0]->nom;
        }
        elseif(isset($requete['flotte'])){
            $flotte = new Flotte((int) $requete['flotte']);
            if($flotte->id_joueur !== $joueur->id){
                return null;
            }
            $ordre['flottes'] = array($flotte->id);
            $ordre['libelle'] = $flotte->nom;
        }
        elseif(isset($requete['type']) && is_string($requete['type']) && isset(Vaisseau::CATALOGUE[$requete['type']])){
            $ordre['nombres'][$requete['type']] = 1;
            $ordre['libelle'] = Vaisseau::CATALOGUE[$requete['type']]['nom'];
        }
        else{
            return null;
        }
        return $ordre;
    }

    private static function systemeDeBase(Joueur $joueur): ?int {
        $idPlanete = Planete::premiereGouvernee($joueur->id);
        if($idPlanete !== null){
            return (new Planete($idPlanete))->id_systeme;
        }
        $planete = $joueur->giveMainPerso()->sur_planete;
        return $planete === null ? null : $planete->id_systeme;
    }

    private static function systeme(Joueur $joueur, Systeme $systeme, array $camps, array $analysees): array {
        $idCamp = $systeme->getIdCamp();
        $planetes = array();
        $moi = false;
        foreach($systeme->planetes as $planete){
            if((int) $planete->gouverneur === $joueur->id){
                $moi = true;
            }
            array_push($planetes, array(
                'id' => (int) $planete->id,
                'nom' => $planete->nom,
                'x' => $planete->x,
                'y' => $planete->y,
                'couleur' => $planete->id_camp === null ? Camp::SANS_CAMP_COULEUR : $camps[$planete->id_camp]->couleur,
                'statut' => $planete->gouverneur === $joueur->id ? self::STATUT_MIEN : ($planete->gouverneur === 0 ? self::STATUT_LIBRE : self::STATUT_OCCUPE),
                'moi' => $planete->gouverneur === $joueur->id,
                'exploree' => $planete->gouverneur === $joueur->id || isset($analysees[$planete->id]),
            ));
        }
        return array(
            'id' => $systeme->id,
            'nom' => $systeme->nom,
            'x' => $systeme->x,
            'y' => $systeme->y,
            'couleur' => $idCamp === null ? Camp::SANS_CAMP_COULEUR : $camps[$idCamp]->couleur,
            'camp' => $idCamp === null ? Camp::SANS_CAMP_NOM : $camps[$idCamp]->nom,
            'moi' => $moi,
            'planetes' => $planetes,
        );
    }

    private static function secondes(Joueur $joueur, Planete $depart, array $types, array $destinations): array {
        $reduction = $depart->reductionTrajet($joueur);
        $secondes = array();
        foreach($destinations as $destination){
            if($destination->id !== $depart->id_systeme){
                $secondes[$destination->id] = self::dureeMaximale($joueur, $types, $depart->systeme->distanceA($destination) * Vaisseau::TRAJET_SECONDES_PAR_UNITE, $reduction);
            }
        }
        return $secondes;
    }

    private static function secondesLocales(Joueur $joueur, Planete $depart, array $types): array {
        $reduction = $depart->reductionTrajet($joueur);
        $secondes = array();
        foreach($depart->systeme->planetes as $destination){
            if($destination->id !== $depart->id){
                $secondes[$destination->id] = self::dureeMaximale($joueur, $types, Planete::secondesEntre($depart->x, $depart->y, $destination->x, $destination->y), $reduction);
            }
        }
        return $secondes;
    }

    private static function dureeMaximale(Joueur $joueur, array $types, int $baseSecondes, int $reduction): int {
        $duree = 0;
        foreach($types as $type){
            $duree = max($duree, Vaisseau::dureeEntre($joueur, $type, $baseSecondes, $reduction));
        }
        return $duree;
    }

    private static function stocks(Joueur $joueur, array $systemes, array $destinations): array {
        $stocks = array();
        foreach(Vaisseau::posees($joueur->id) as $planeteId => $types){
            $planete = new Planete($planeteId);
            if(!isset($systemes[$planete->id_systeme])){
                continue;
            }
            $vaisseaux = array();
            foreach($types as $type => $nombre){
                array_push($vaisseaux, array(
                    'type' => $type,
                    'nom' => Vaisseau::CATALOGUE[$type]['nom'],
                    'nombre' => $nombre,
                    'combat' => in_array($type, Combat::ESCADRE, true),
                    'secondes' => self::secondes($joueur, $planete, array($type), $destinations),
                    'secondesLocales' => self::secondesLocales($joueur, $planete, array($type)),
                ));
            }
            array_push($stocks, array('planete' => $planete->id, 'systeme' => $planete->id_systeme, 'nom' => $planete->nom, 'vaisseaux' => $vaisseaux));
        }
        return $stocks;
    }

    private static function flottes(Joueur $joueur, array $systemes, array $destinations): array {
        $flottes = array();
        foreach(Flotte::liste($joueur->id) as $ligne){
            $flotte = new Flotte($ligne->id);
            $planeteId = $flotte->planeteAuRepos();
            if($planeteId === null){
                continue;
            }
            $planete = new Planete($planeteId);
            if(!isset($systemes[$planete->id_systeme])){
                continue;
            }
            $composition = array();
            foreach($flotte->composition() as $type => $nombre){
                array_push($composition, array('nom' => Vaisseau::CATALOGUE[$type]['nom'], 'nombre' => $nombre));
            }
            array_push($flottes, array(
                'id' => $flotte->id,
                'nom' => $flotte->nom,
                'planete' => $planete->id,
                'systeme' => $planete->id_systeme,
                'composition' => $composition,
                'secondes' => self::secondes($joueur, $planete, array_keys($flotte->composition()), $destinations),
                'secondesLocales' => self::secondesLocales($joueur, $planete, array_keys($flotte->composition())),
            ));
        }
        return $flottes;
    }

    private static function enRoute(Joueur $joueur, array $destinations): array {
        $trajets = array();
        foreach(Vaisseau::enRoute($joueur->id) as $ligne){
            $cle = $ligne->id_planete.'|'.$ligne->id_destination.'|'.$ligne->arrivee;
            $libelle = $ligne->nombre.' × '.Vaisseau::CATALOGUE[$ligne->type]['nom'];
            if(isset($trajets[$cle])){
                $trajets[$cle]['libelle'] .= ', '.$libelle;
                continue;
            }
            $trajets[$cle] = self::trajet($destinations, $libelle, $ligne, (bool) $ligne->exploration);
        }
        foreach(Flotte::liste($joueur->id) as $ligne){
            $flotte = new Flotte($ligne->id);
            $ligneFlotte = $flotte->enRoute();
            if($ligneFlotte !== null){
                $trajets['flotte|'.$flotte->id] = self::trajet($destinations, $flotte->nom, $ligneFlotte);
            }
        }
        return array_values($trajets);
    }

    private static function trajet(array $destinations, string $libelle, object $ligne, bool $expedition = false): array {
        $de = (new Planete($ligne->id_planete))->id_systeme;
        $vers = (new Planete($ligne->id_destination))->id_systeme;
        return array(
            'libelle' => $libelle,
            'expedition' => $expedition,
            'de' => $de,
            'vers' => isset($destinations[$vers]) ? $vers : null,
            'depart' => $ligne->depart === null ? null : strtotime($ligne->depart),
            'arrivee' => strtotime($ligne->arrivee),
        );
    }

}
