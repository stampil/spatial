<?php

class Combat {

    const PERCENT = 100;
    const ALEA_POURCENT = 20;
    const BONUS_PHASE_POURCENT = 25;
    const HEROS_BONUS_PAR_ETOILE_POURCENT = 5;
    const EPAVE_CREDITS_PAR_POINT = 10;
    const EPAVE_VAISSEAU_POURCENT = 50;
    const ESCADRE = array('chasseur', 'fregate', 'capital');
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
        $stock = Vaisseau::stock($planete->id, $joueur->id, $flotteId);
        $nombres = array();
        foreach(self::ESCADRE as $type){
            $nombres[$type] = $stock[$type] ?? 0;
        }
        if(array_sum($nombres) === 0){
            return 'Aucun vaisseau de combat sur cette planète.';
        }
        if($planete->bouclier + $planete->canon === 0){
            return "Cette planète n'a plus de défense.";
        }

        $bonusHeros = Perso::boostHeros($joueur, $planete, Perso::CARACTERISTIQUE_FORCE) * self::HEROS_BONUS_PAR_ETOILE_POURCENT;
        $resistance = $planete->bonus === Bonus::CITADELLE ? Bonus::pourcent(Bonus::CITADELLE) : 0;
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

        $db = MyPDO::getInstance();
        $query = "UPDATE " . MyPDO::DB_FLAG . "planete SET bouclier=?, canon=?, defense_maj=COALESCE(defense_maj, ?) WHERE id=? AND bouclier=? AND canon=?";
        $db->query($query,$bouclier,$canon,Tools::now(),$planete->id,$planete->bouclier,$planete->canon);
        if($db->lastRowCount !== 1){
            return 'La situation a changé sur cette planète : recommencez.';
        }

        $pertes = self::pertes($nombres, $planete->canon);
        $rapport = array();
        foreach($pertes as $type => $perdus){
            $query = "DELETE FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND id_planete=? AND type=? AND arrivee IS NULL AND id_flotte <=> ? ORDER BY id LIMIT " . $perdus;
            $db->query($query,$joueur->id,$planete->id,$type,$flotteId);
            array_push($rapport, $perdus.' × '.Vaisseau::CATALOGUE[$type]['nom']);
        }
        $message = 'Combat sur '.$planete->nom.' : défense restante, bouclier '.$bouclier.' et canon '.$canon.'. Pertes : '.(count($rapport) > 0 ? implode(', ', $rapport) : 'aucune').'.';
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
        $troupes = Vaisseau::stock($planete->id, $joueur->id)[Vaisseau::TROUPE] ?? 0;
        if($troupes === 0){
            return "Aucune troupe d'assaut sur cette planète.";
        }
        $db = MyPDO::getInstance();
        $query = "DELETE FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_joueur=? AND id_planete=? AND type=? AND arrivee IS NULL ORDER BY id LIMIT " . $troupes;
        $db->query($query,$joueur->id,$planete->id,Vaisseau::TROUPE);
        if($db->lastRowCount !== $troupes){
            return 'La situation a changé sur cette planète : recommencez.';
        }

        $force = Vaisseau::CATALOGUE[Vaisseau::TROUPE]['force'];
        $attaque = self::alea($troupes * $force);
        if($attaque > $planete->troupes * $force){
            $survivants = max(1, $troupes - $planete->troupes);
            if(!$planete->capturer($joueur, $survivants)){
                return 'La planète a changé de main : les troupes sont perdues.';
            }
            $message = 'Débarquement réussi : vous gouvernez maintenant '.$planete->nom.' ('.$survivants.' '.__('troupe','troupes',$survivants).' en garnison). La planète est révoltée à '.Planete::CAPTURE_REVOLTE.' %.';
            $garnison = 0;
        }
        else{
            $abattues = min($planete->troupes, intdiv($attaque, $force));
            $query = "UPDATE " . MyPDO::DB_FLAG . "planete SET troupes=troupes-?, defense_maj=COALESCE(defense_maj, ?) WHERE id=?";
            $db->query($query,$abattues,Tools::now(),$planete->id);
            $message = 'Débarquement repoussé sur '.$planete->nom.' : '.($troupes > 1 ? 'vos ' : 'votre ').$troupes.' '.__('troupe','troupes',$troupes).' '.__('perdue','perdues',$troupes).', la garnison perd '.$abattues.' '.__('troupe','troupes',$abattues).'.';
            $garnison = $planete->troupes - $abattues;
        }
        Tools::addMessage($message);
        Tools::addRapport(array(
            'titre' => 'Débarquement sur '.$planete->nom,
            'barres' => array(array('cle' => 'troupes', 'nom' => 'Garnison', 'maximum' => $planete->troupes)),
            'etapes' => array(array('texte' => $troupes.' '.__('troupe','troupes',$troupes).' d\'assaut : '.$attaque.' de force', 'valeurs' => array('troupes' => $garnison))),
            'bilan' => $message,
        ));
        return null;
    }

    public static function bataille(string $titre, array $camps): array {
        $restants = array();
        $maximum = array();
        $pertes = array();
        $bonusDePhase = array();
        foreach(self::ADVERSAIRE as $camp => $adversaire){
            $restants[$camp] = $camps[$camp]['nombres'];
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
                foreach(self::detruire($restants[$adversaire], $degats[$camp]) as $perdu => $nombre){
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
        $vainqueur = null;
        if($forceAttaquant > $forceDefenseur){
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
        }
        return array(
            'pertes' => $pertes,
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

    public static function forceTotale(array $nombres): int {
        $force = 0;
        foreach(self::ESCADRE as $type){
            $force += $nombres[$type] * Vaisseau::CATALOGUE[$type]['force'];
        }
        return $force;
    }

    public static function detruire(array &$nombres, int $degats): array {
        $perdus = array();
        foreach(self::ESCADRE as $type){
            $force = Vaisseau::CATALOGUE[$type]['force'];
            $perdus[$type] = min($nombres[$type], intdiv($degats, $force));
            $nombres[$type] -= $perdus[$type];
            $degats -= $perdus[$type] * $force;
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
