<?php

class Carte {

    public static function donnees(Joueur $joueur): array {
        $camps = array();
        foreach(Camp::liste() as $camp){
            $camps[$camp->id] = $camp;
        }
        $systemes = array();
        foreach(Systeme::listeVisibles($joueur->id) as $systeme){
            $systemes[$systeme->id] = $systeme;
        }
        $voisin = null;
        $destinations = $systemes;
        if($joueur->id_systeme_voisin !== null && !isset($systemes[$joueur->id_systeme_voisin])){
            $voisin = new Systeme($joueur->id_systeme_voisin);
            $destinations[$voisin->id] = $voisin;
        }
        $gouverneurs = self::nomsGouverneurs($systemes);

        $donnees = array(
            'maintenant' => time(),
            'base' => self::systemeDeBase($joueur),
            'systemes' => array(),
            'voisin' => null,
            'stocks' => self::stocks($joueur, $systemes, $destinations),
            'flottes' => self::flottes($joueur, $systemes, $destinations),
            'enRoute' => self::enRoute($joueur, $destinations),
        );
        foreach($systemes as $systeme){
            array_push($donnees['systemes'], self::systeme($joueur, $systeme, $camps, $gouverneurs));
        }
        if($voisin !== null){
            $donnees['voisin'] = array('id' => $voisin->id, 'x' => $voisin->x, 'y' => $voisin->y, 'planete' => $voisin->planetes[0]->id);
        }
        return $donnees;
    }

    private static function systemeDeBase(Joueur $joueur): ?int {
        $idPlanete = Planete::premiereGouvernee($joueur->id);
        if($idPlanete !== null){
            return (new Planete($idPlanete))->id_systeme;
        }
        $planete = $joueur->giveMainPerso()->sur_planete;
        return $planete === null ? null : $planete->id_systeme;
    }

    private static function nomsGouverneurs(array $systemes): array {
        $ids = array();
        foreach($systemes as $systeme){
            foreach($systeme->planetes as $planete){
                if((int) $planete->gouverneur !== 0){
                    $ids[(int) $planete->gouverneur] = (int) $planete->gouverneur;
                }
            }
        }
        if(count($ids) === 0){
            return array();
        }
        $marques = implode(',', array_fill(0, count($ids), '?'));
        $noms = array();
        foreach(MyPDO::getInstance()->query("SELECT id, nom FROM " . MyPDO::DB_FLAG . "joueur WHERE id IN ($marques)", array_values($ids)) as $ligne){
            $noms[$ligne->id] = $ligne->nom;
        }
        return $noms;
    }

    private static function systeme(Joueur $joueur, Systeme $systeme, array $camps, array $gouverneurs): array {
        $idCamp = $systeme->getIdCamp();
        $planetes = array();
        $moi = false;
        foreach($systeme->planetes as $planete){
            $estMoi = (int) $planete->gouverneur === $joueur->id;
            $moi = $moi || $estMoi;
            $bonus = null;
            if($planete->bonus !== null){
                $bonus = array('nom' => Bonus::libelle($planete->bonus), 'description' => Bonus::description($planete->bonus));
            }
            array_push($planetes, array(
                'id' => (int) $planete->id,
                'nom' => $planete->nom,
                'slot' => (int) $planete->slot,
                'moi' => $estMoi,
                'gouverneur' => $gouverneurs[(int) $planete->gouverneur] ?? null,
                'couleur' => $planete->id_camp === null ? Camp::SANS_CAMP_COULEUR : $camps[$planete->id_camp]->couleur,
                'camp' => $planete->id_camp === null ? Camp::SANS_CAMP_NOM : $camps[$planete->id_camp]->nom,
                'bouclier' => (int) $planete->bouclier,
                'canon' => (int) $planete->canon,
                'troupes' => (int) $planete->troupes,
                'revolte' => (int) $planete->perc_revolte,
                'bonus' => $bonus,
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
            $duree = 0;
            foreach($types as $type){
                $duree = max($duree, Vaisseau::dureeEntre($joueur, $type, $depart->systeme->distanceA($destination), $reduction));
            }
            $secondes[$destination->id] = $duree;
        }
        return $secondes;
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
                $trajets['flotte|'.$flotte->id] = self::trajet($destinations, 'Flotte '.$flotte->nom, $ligneFlotte);
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
