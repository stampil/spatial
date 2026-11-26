<?php

class Combat {

    const PERCENT = 100;
    const ALEA_POURCENT = 20;
    const BONUS_PHASE_POURCENT = 25;
    const HEROS_BONUS_PAR_ETOILE_POURCENT = 5;
    const EPAVE_CREDITS_PAR_POINT = 10;
    const EPAVE_VAISSEAU_POURCENT = 50;
    const ESCADRE = array('chasseur', 'fregate', 'capital', 'restrictor');
    const BONUS_CHASSEUR_CAPITAL_POURCENT = 100;
    const ILLUSTRATION_SPATIALE = 'combat';
    const ILLUSTRATION_TERRESTRE = 'combat-terrestre';
    const FUITE_RESTE_ADVERSAIRE_POURCENT = 30;
    const FUITE_RESTE_DEPART_POURCENT = 30;
    const ATTAQUANT = 'attaquant';
    const DEFENSEUR = 'defenseur';
    const ADVERSAIRE = array(self::ATTAQUANT => self::DEFENSEUR, self::DEFENSEUR => self::ATTAQUANT);

    public static function attaquer(Joueur $joueur, Planete $planete, ?Flotte $flotte = null): ?string {
        if($flotte !== null && $flotte->id_joueur !== $joueur->id){
            return 'Cette flotte ne vous appartient pas.';
        }
        if($planete->gouverneur === $joueur->id){
            return 'Vous gouvernez déjà cette planète.';
        }
        if(!$planete->systeme->estConnuDe($joueur->id)){
            return 'Cette planète est inconnue.';
        }
        $flotteId = $flotte === null ? null : $flotte->id;
        $nombres = Vaisseau::combattants($planete->id, $joueur->id, $flotteId);
        if(array_sum($nombres) === 0){
            return 'Aucun vaisseau de combat engagé sur cette planète.';
        }
        $defenseurs = self::defenseurs($planete);
        if($planete->bouclier + $planete->canon === 0 && array_sum($defenseurs) === 0){
            return "Cette planète n'a plus de défense.";
        }

        $bonusHeros = Perso::boostHeros($joueur, $planete, Perso::CARACTERISTIQUE_FORCE, self::HEROS_BONUS_PAR_ETOILE_POURCENT);
        $resistance = $planete->bonus === Bonus::CITADELLE ? Bonus::pourcent(Bonus::CITADELLE) : 0;
        $pertesBataille = array_fill_keys(self::ESCADRE, 0);
        $bilanBataille = '';
        if(array_sum($defenseurs) > 0){
            $bataille = self::bataillePlanete($joueur, $planete, $nombres, $defenseurs, 'Flotte défenseuse sur '.$planete->nom);
            $pertesBataille = $bataille['pertes'][self::ATTAQUANT];
            if($bataille['vainqueur'] !== self::ATTAQUANT){
                Vaisseau::retirerCombattants($planete->id, $joueur->id, $flotteId, $pertesBataille);
                if($bataille['fuite'][self::ATTAQUANT]){
                    Vaisseau::fuir($joueur, $planete, $planete->refugePour($joueur), $flotteId);
                }
                Tools::addMessage($bataille['bilan']);
                return null;
            }
            $joueur->crediter($bataille['epaves']);
            $bilanBataille = $bataille['bilan'].' ';
            foreach(self::ESCADRE as $type){
                $nombres[$type] -= $pertesBataille[$type];
            }
        }
        $etapes = array();
        $degats = self::puissance($nombres['chasseur'], 'chasseur', $bonusHeros, $resistance);
        list($canon, $bouclier) = self::infliger($degats, $planete->canon, $planete->bouclier);
        self::noterEtape($etapes, $nombres['chasseur'], 'chasseur', $degats, $bouclier, $canon);
        $bonus = $canon === 0 ? self::BONUS_PHASE_POURCENT : 0;
        $degats = self::puissance($nombres['fregate'], 'fregate', $bonus + $bonusHeros, $resistance);
        list($bouclier, $canon) = self::infliger($degats, $bouclier, $canon);
        self::noterEtape($etapes, $nombres['fregate'], 'fregate', $degats, $bouclier, $canon);
        $bonus = $bouclier === 0 ? self::BONUS_PHASE_POURCENT : 0;
        $degats = self::puissance($nombres['capital'], 'capital', $bonus + $bonusHeros, $resistance);
        list($canon, $bouclier) = self::infliger($degats, $canon, $bouclier);
        self::noterEtape($etapes, $nombres['capital'], 'capital', $degats, $bouclier, $canon);
        $degats = self::puissance($nombres[Vaisseau::RESTRICTOR], Vaisseau::RESTRICTOR, $bonusHeros, $resistance);
        list($bouclier, $canon) = self::infliger($degats, $bouclier, $canon);
        self::noterEtape($etapes, $nombres[Vaisseau::RESTRICTOR], Vaisseau::RESTRICTOR, $degats, $bouclier, $canon);

        $db = MyPDO::getInstance();
        $query = "UPDATE " . MyPDO::DB_FLAG . "planete SET bouclier=?, canon=?, defense_maj=COALESCE(defense_maj, ?) WHERE id=? AND bouclier=? AND canon=?";
        $db->query($query,$bouclier,$canon,Tools::now(),$planete->id,$planete->bouclier,$planete->canon);
        if($db->lastRowCount !== 1){
            return 'La situation a changé sur cette planète : recommencez.';
        }

        $pertes = $pertesBataille;
        foreach(self::pertes($nombres, $planete->canon) as $type => $perdus){
            $pertes[$type] += $perdus;
        }
        $rapport = array();
        foreach($pertes as $type => $perdus){
            if($perdus > 0){
                array_push($rapport, $perdus.' × '.Vaisseau::CATALOGUE[$type]['nom']);
            }
        }
        Vaisseau::retirerCombattants($planete->id, $joueur->id, $flotteId, $pertes);
        $message = $bilanBataille.'Combat sur '.$planete->nom.' : défense restante, bouclier '.$bouclier.' et canon '.$canon.'. Pertes : '.(count($rapport) > 0 ? implode(', ', $rapport) : 'aucune').'.';
        if($resistance > 0){
            $message .= ' Citadelle : défense +'.$resistance.' %.';
        }
        if($bonusHeros > 0){
            $message .= ' Héros présent : +'.$bonusHeros.' % de puissance.';
        }
        if($bouclier + $canon === 0){
            $epaves = ($planete->bouclier + $planete->canon) * self::EPAVE_CREDITS_PAR_POINT;
            $joueur->crediter($epaves);
            $message .= ' Défense détruite : épaves récupérées, +'.$epaves.' cr.';
        }
        Tools::addMessage($message);
        Tools::addRapport(array(
            'titre' => 'Bataille sur '.$planete->nom,
            'barres' => array(
                array('cle' => 'bouclier', 'nom' => 'Bouclier', 'maximum' => $planete->bouclier),
                array('cle' => 'canon', 'nom' => 'Canon', 'maximum' => $planete->canon),
            ),
            'etapes' => $etapes,
            'bilan' => $message,
        ));
        if($flotte !== null && count($flotte->composition()) === 0){
            $flotte->dissoudre();
        }
        return null;
    }

    private static function bataillePlanete(Joueur $joueur, Planete $planete, array $nombres, array $defenseurs, string $titre): array {
        $bonusHeros = Perso::boostHeros($joueur, $planete, Perso::CARACTERISTIQUE_FORCE, self::HEROS_BONUS_PAR_ETOILE_POURCENT);
        $bataille = self::bataille($titre, array(
            self::ATTAQUANT => array('nom' => $joueur->nom, 'nombres' => $nombres, 'bonus' => $bonusHeros, 'fuite' => $planete->refugePour($joueur) !== null),
            self::DEFENSEUR => array('nom' => self::nomDefenseur($planete), 'nombres' => $defenseurs, 'bonus' => self::bonusDefenseur($planete), 'fuite' => $planete->gouverneur === 0 && $planete->refugeIA() !== null),
        ));
        self::retirerDefenseurs($planete, $bataille['pertes'][self::DEFENSEUR], $joueur);
        if($bataille['fuite'][self::DEFENSEUR]){
            self::faireFuirIA($planete);
        }
        if($planete->gouverneur === 0 && self::forceTotale($bataille['survivants'][self::DEFENSEUR]) === 0 && $bataille['survivants'][self::ATTAQUANT][Vaisseau::RESTRICTOR] > 0){
            MyPDO::getInstance()->query("UPDATE " . MyPDO::DB_FLAG . "joueur SET tuto_aneantie=1 WHERE id=?",$joueur->id);
        }
        $bataille['rapport']['illustration'] = self::ILLUSTRATION_SPATIALE;
        Tools::addRapport($bataille['rapport']);
        return $bataille;
    }

    private static function faireFuirIA(Planete $planete): void {
        $refuge = $planete->refugeIA();
        if($refuge === null){
            return;
        }
        $db = MyPDO::getInstance();
        $restants = (int) $db->query("SELECT flotte_ia FROM " . MyPDO::DB_FLAG . "planete WHERE id=?",$planete->id)[0]->flotte_ia;
        $db->query("UPDATE " . MyPDO::DB_FLAG . "planete SET flotte_ia=0 WHERE id=?",$planete->id);
        $db->query("UPDATE " . MyPDO::DB_FLAG . "planete SET flotte_ia=flotte_ia+?, defense_maj=COALESCE(defense_maj, ?) WHERE id=?",$restants,Tools::now(),$refuge->id);
    }

    public static function arrivee(Joueur $joueur, Planete $planete): void {
        if($planete->gouverneur === $joueur->id){
            return;
        }
        $defenseurs = self::defenseurs($planete);
        $nombres = Vaisseau::escadre($planete->id, $joueur->id);
        if(array_sum($defenseurs) === 0 || array_sum($nombres) === 0){
            return;
        }
        $bataille = self::bataillePlanete($joueur, $planete, $nombres, $defenseurs, 'Combat spatial sur '.$planete->nom);
        Vaisseau::detruire($planete->id, $joueur->id, $bataille['pertes'][self::ATTAQUANT]);
        if($bataille['fuite'][self::ATTAQUANT]){
            Vaisseau::fuirTous($joueur, $planete, $planete->refugePour($joueur));
        }
        if($bataille['vainqueur'] === self::ATTAQUANT){
            $joueur->crediter($bataille['epaves']);
        }
        Tools::addMessage($bataille['bilan']);
    }

    public static function defenseurs(Planete $planete): array {
        if($planete->gouverneur === 0){
            $nombres = array_fill_keys(self::ESCADRE, 0);
            $nombres[Vaisseau::CHASSEUR] = $planete->flotte_ia;
            return $nombres;
        }
        return Vaisseau::escadre($planete->id, $planete->gouverneur);
    }

    private static function nomDefenseur(Planete $planete): string {
        if($planete->gouverneur === 0){
            return (new Camp($planete->id_camp))->nom;
        }
        return (new Joueur($planete->gouverneur))->nom;
    }

    private static function bonusDefenseur(Planete $planete): int {
        if($planete->gouverneur === 0){
            return $planete->bonus === Bonus::CITADELLE ? Bonus::pourcent(Bonus::CITADELLE) : 0;
        }
        return Menace::bonusDefense(new Joueur($planete->gouverneur), $planete);
    }

    private static function retirerDefenseurs(Planete $planete, array $pertes, Joueur $attaquant): void {
        $total = array_sum($pertes);
        if($total === 0){
            return;
        }
        if($planete->gouverneur === 0){
            $query = "UPDATE " . MyPDO::DB_FLAG . "planete SET flotte_ia=GREATEST(0, flotte_ia-?), defense_maj=COALESCE(defense_maj, ?) WHERE id=?";
            MyPDO::getInstance()->query($query,$pertes[Vaisseau::CHASSEUR],Tools::now(),$planete->id);
            return;
        }
        Vaisseau::detruire($planete->id, $planete->gouverneur, $pertes);
        Journal::ajouter($planete->gouverneur, $attaquant->nom.' attaque '.$planete->nom.' : '.$total.' '.__('vaisseau','vaisseaux',$total).' détruit(s).');
    }

    public static function debarquer(Joueur $joueur, Planete $planete): ?string {
        if($planete->gouverneur === $joueur->id){
            return 'Vous gouvernez déjà cette planète.';
        }
        if(!$planete->systeme->estConnuDe($joueur->id)){
            return 'Cette planète est inconnue.';
        }
        if($planete->bouclier + $planete->canon > 0){
            return "La défense planétaire tient encore : détruisez-la d'abord.";
        }
        if(array_sum(self::defenseurs($planete)) > 0){
            return 'La flotte défenseuse tient encore : détruisez-la d\'abord.';
        }
        if($planete->pnj_endurance !== null){
            return 'Un gouverneur est en place : assassinez-le d\'abord.';
        }
        if($planete->nb_bouclier_planetaire > 0){
            return 'Un bouclier planétaire protège la planète : aucune troupe ne peut débarquer, sabotez-le d\'abord.';
        }
        $troupes = Vaisseau::troupesEnBarge($planete->id, $joueur->id);
        if($troupes === 0){
            return "Aucune troupe d'assaut embarquée dans une barge de débarquement sur cette planète.";
        }
        $db = MyPDO::getInstance();
        $db->query("DELETE e" . Vaisseau::TROUPES_EN_BARGE,$joueur->id,$planete->id,Vaisseau::TROUPE,Vaisseau::BARGE);
        if($db->lastRowCount !== $troupes){
            return 'La situation a changé sur cette planète : recommencez.';
        }

        $force = Vaisseau::CATALOGUE[Vaisseau::TROUPE]['force'];
        $attaque = self::alea($troupes * $force);
        $conquete = false;
        if($attaque > $planete->troupes * $force){
            $survivants = max(1, $troupes - $planete->troupes);
            if(!$planete->capturer($joueur, $survivants)){
                return 'La planète a changé de main : les troupes sont perdues.';
            }
            $conquete = true;
            $message = 'Débarquement réussi : vous gouvernez maintenant '.$planete->nom.' ('.$survivants.' '.__('troupe','troupes',$survivants).' en garnison). La planète est révoltée à '.Planete::CAPTURE_REVOLTE.' %.';
            $garnison = 0;
        }
        else{
            $abattues = min($planete->troupes, intdiv($attaque, $force));
            $query = "UPDATE " . MyPDO::DB_FLAG . "planete SET troupes=troupes-?, defense_maj=COALESCE(defense_maj, ?) WHERE id=?";
            $db->query($query,$abattues,Tools::now(),$planete->id);
            $message = 'Débarquement repoussé sur '.$planete->nom.' : '.__('votre', 'vos', $troupes).' '.$troupes.' '.__('troupe','troupes',$troupes).' '.__('perdue','perdues',$troupes).', la garnison perd '.$abattues.' '.__('troupe','troupes',$abattues).'.';
            $garnison = $planete->troupes - $abattues;
        }
        Tools::addMessage($message);
        $rapport = array(
            'titre' => 'Débarquement sur '.$planete->nom,
            'illustration' => self::ILLUSTRATION_TERRESTRE,
            'barres' => array(array('cle' => 'troupes', 'nom' => 'Garnison', 'maximum' => $planete->troupes)),
            'etapes' => array(array('texte' => $troupes.' '.__('troupe','troupes',$troupes).' d\'assaut : '.$attaque.' de force', 'valeurs' => array('troupes' => $garnison))),
            'bilan' => $message,
        );
        if($conquete){
            $rapport['id_camp'] = $joueur->id_camp;
            $rapport['illustration_finale'] = Vue::fichierFond(Planete::ILLUSTRATION_CONQUISE);
        }
        Tools::addRapport($rapport);
        return null;
    }

    public static function bataille(string $titre, array $camps): array {
        $restants = array();
        $maximum = array();
        $pertes = array();
        $bonusDePhase = array();
        foreach(self::ADVERSAIRE as $camp => $adversaire){
            $restants[$camp] = array_merge(array_fill_keys(self::ESCADRE, 0), $camps[$camp]['nombres']);
            $maximum[$camp] = self::forceTotale($restants[$camp]);
            $pertes[$camp] = array_fill_keys(self::ESCADRE, 0);
            $bonusDePhase[$camp] = 0;
        }
        $etapes = array();
        foreach(self::ESCADRE as $type){
            $presents = 0;
            $degats = array();
            foreach(self::ADVERSAIRE as $camp => $adversaire){
                $presents += $restants[$camp][$type];
                $degats[$camp] = self::puissance($restants[$camp][$type], $type, $camps[$camp]['bonus'] + $bonusDePhase[$camp], 0);
            }
            if($presents === 0){
                continue;
            }
            $texte = Vaisseau::CATALOGUE[$type]['nom'].' :';
            $valeurs = array();
            foreach(self::ADVERSAIRE as $camp => $adversaire){
                $texte .= ' '.$camps[$camp]['nom'].' '.$restants[$camp][$type].' ('.$degats[$camp].' de dégâts),';
                $bonusCapital = $type === Vaisseau::CHASSEUR ? self::BONUS_CHASSEUR_CAPITAL_POURCENT : 0;
                foreach(self::detruire($restants[$adversaire], $degats[$camp], $bonusCapital) as $perdu => $nombre){
                    $pertes[$adversaire][$perdu] += $nombre;
                }
                $bonusDePhase[$camp] = 0;
            }
            foreach(self::ADVERSAIRE as $camp => $adversaire){
                $valeurs[$camp] = self::forceTotale($restants[$camp]);
            }
            if($degats[self::ATTAQUANT] > $degats[self::DEFENSEUR]){
                $bonusDePhase[self::ATTAQUANT] = self::BONUS_PHASE_POURCENT;
            }
            elseif($degats[self::DEFENSEUR] > $degats[self::ATTAQUANT]){
                $bonusDePhase[self::DEFENSEUR] = self::BONUS_PHASE_POURCENT;
            }
            array_push($etapes, array('texte' => rtrim($texte, ','), 'valeurs' => $valeurs));
        }

        $forceAttaquant = self::forceTotale($restants[self::ATTAQUANT]);
        $forceDefenseur = self::forceTotale($restants[self::DEFENSEUR]);
        $fuite = array(self::ATTAQUANT => false, self::DEFENSEUR => false);
        foreach(self::ADVERSAIRE as $camp => $adversaire){
            $fuite[$camp] = self::fuiteDeCamp($restants[$camp], $maximum[$camp], $restants[$adversaire], $camps[$camp]['fuite'] ?? false);
        }
        $vainqueur = null;
        if($fuite[self::ATTAQUANT]){
            $vainqueur = self::DEFENSEUR;
        }
        elseif($fuite[self::DEFENSEUR]){
            $vainqueur = self::ATTAQUANT;
        }
        elseif($forceAttaquant > $forceDefenseur){
            $vainqueur = self::ATTAQUANT;
        }
        elseif($forceDefenseur > 0){
            $vainqueur = self::DEFENSEUR;
        }
        $epaves = 0;
        $bilan = 'Les deux flottes sont anéanties.';
        if($vainqueur !== null){
            $epaves = self::valeurEpaves($pertes[self::ADVERSAIRE[$vainqueur]]);
            $bilan = 'Victoire de '.$camps[$vainqueur]['nom'].' : '.$camps[self::ATTAQUANT]['nom'].' perd '.array_sum($pertes[self::ATTAQUANT]).' '.__('vaisseau','vaisseaux',array_sum($pertes[self::ATTAQUANT])).', '.$camps[self::DEFENSEUR]['nom'].' perd '.array_sum($pertes[self::DEFENSEUR]).' '.__('vaisseau','vaisseaux',array_sum($pertes[self::DEFENSEUR])).'. Épaves récupérées : +'.$epaves.' cr.';
            foreach(self::ADVERSAIRE as $camp => $adversaire){
                if($fuite[$camp]){
                    $bilan .= ' Les survivants de '.$camps[$camp]['nom'].' battent en retraite.';
                }
            }
        }
        return array(
            'pertes' => $pertes,
            'survivants' => $restants,
            'fuite' => $fuite,
            'vainqueur' => $vainqueur,
            'epaves' => $epaves,
            'bilan' => $bilan,
            'rapport' => array(
                'titre' => $titre,
                'barres' => array(
                    array('cle' => self::ATTAQUANT, 'nom' => $camps[self::ATTAQUANT]['nom'], 'maximum' => $maximum[self::ATTAQUANT]),
                    array('cle' => self::DEFENSEUR, 'nom' => $camps[self::DEFENSEUR]['nom'], 'maximum' => $maximum[self::DEFENSEUR]),
                ),
                'etapes' => $etapes,
                'bilan' => $bilan,
            ),
        );
    }

    public static function fuiteDeCamp(array $restants, int $maximum, array $restantsAdversaire, bool $refugeDisponible): bool {
        $reste = self::forceTotale($restants);
        return $refugeDisponible && $restantsAdversaire[Vaisseau::RESTRICTOR] === 0 && $reste > 0
            && $reste * self::PERCENT < $maximum * self::FUITE_RESTE_DEPART_POURCENT
            && $reste * self::PERCENT < self::forceTotale($restantsAdversaire) * self::FUITE_RESTE_ADVERSAIRE_POURCENT;
    }

    public static function forceTotale(array $nombres): int {
        $force = 0;
        foreach(self::ESCADRE as $type){
            $force += $nombres[$type] * Vaisseau::CATALOGUE[$type]['force'];
        }
        return $force;
    }

    public static function detruire(array &$nombres, int $degats, int $bonusCapitalPourcent = 0): array {
        $perdus = array();
        foreach(self::ESCADRE as $type){
            $force = Vaisseau::CATALOGUE[$type]['force'];
            $multiplicateur = $type === 'capital' ? self::PERCENT + $bonusCapitalPourcent : self::PERCENT;
            $efficaces = intdiv($degats * $multiplicateur, self::PERCENT);
            $perdus[$type] = min($nombres[$type], intdiv($efficaces, $force));
            $nombres[$type] -= $perdus[$type];
            $consommes = intdiv($perdus[$type] * $force * self::PERCENT + $multiplicateur - 1, $multiplicateur);
            $degats = max(0, $degats - $consommes);
        }
        return $perdus;
    }

    private static function valeurEpaves(array $pertes): int {
        $prix = 0;
        foreach($pertes as $type => $nombre){
            $prix += $nombre * Vaisseau::CATALOGUE[$type]['prix'];
        }
        return intdiv($prix * self::EPAVE_VAISSEAU_POURCENT, self::PERCENT);
    }

    public static function infliger(int $degats, int $principal, int $secondaire): array {
        $surplus = max(0, $degats - $principal);
        return array(max(0, $principal - $degats), max(0, $secondaire - $surplus));
    }

    private static function puissance(int $nombre, string $type, int $bonusPourcent, int $resistancePourcent): int {
        $force = $nombre * Vaisseau::CATALOGUE[$type]['force'];
        $puissance = intdiv(self::alea($force) * (self::PERCENT + $bonusPourcent), self::PERCENT);
        return intdiv($puissance * self::PERCENT, self::PERCENT + $resistancePourcent);
    }

    private static function noterEtape(array &$etapes, int $nombre, string $type, int $degats, int $bouclier, int $canon): void {
        if($nombre === 0){
            return;
        }
        array_push($etapes, array(
            'texte' => $nombre.' × '.Vaisseau::CATALOGUE[$type]['nom'].' : '.$degats.' de dégâts',
            'valeurs' => array('bouclier' => $bouclier, 'canon' => $canon),
        ));
    }

    public static function alea(int $force): int {
        return intdiv($force * random_int(self::PERCENT - self::ALEA_POURCENT, self::PERCENT + self::ALEA_POURCENT), self::PERCENT);
    }

    private static function pertes(array $nombres, int $canon): array {
        $pertes = array();
        $aRetirer = $canon;
        foreach(self::ESCADRE as $type){
            if($nombres[$type] < 1 || $aRetirer <= 0){
                continue;
            }
            $force = Vaisseau::CATALOGUE[$type]['force'];
            $perdus = min($nombres[$type], intdiv($aRetirer + $force - 1, $force));
            $pertes[$type] = $perdus;
            $aRetirer -= $perdus * $force;
        }
        return $pertes;
    }

}
