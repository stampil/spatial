<?php

class Combat {

    const PERCENT = 100;
    const ALEA_POURCENT = 20;
    const BONUS_PHASE_POURCENT = 25;
    const HEROS_BONUS_PAR_ETOILE_POURCENT = 5;
    const EPAVE_CREDITS_PAR_POINT = 10;
    const ESCADRE = array('chasseur', 'fregate', 'capital');

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
        $query = "UPDATE " . MyPDO::DB_FLAG . "planete SET bouclier=?, canon=? WHERE id=? AND bouclier=? AND canon=?";
        $db->query($query,$bouclier,$canon,$planete->id,$planete->bouclier,$planete->canon);
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
            $message = 'Débarquement réussi : vous gouvernez maintenant '.$planete->nom.' ('.$survivants.' troupe(s) en garnison). La planète est révoltée à '.Planete::CAPTURE_REVOLTE.' %.';
            $garnison = 0;
        }
        else{
            $abattues = min($planete->troupes, intdiv($attaque, $force));
            $query = "UPDATE " . MyPDO::DB_FLAG . "planete SET troupes=troupes-? WHERE id=?";
            $db->query($query,$abattues,$planete->id);
            $message = 'Débarquement repoussé sur '.$planete->nom.' : vos '.$troupes.' troupe(s) sont perdues, la garnison perd '.$abattues.' troupe(s).';
            $garnison = $planete->troupes - $abattues;
        }
        Tools::addMessage($message);
        Tools::addRapport(array(
            'titre' => 'Débarquement sur '.$planete->nom,
            'barres' => array(array('cle' => 'troupes', 'nom' => 'Garnison', 'maximum' => $planete->troupes)),
            'etapes' => array(array('texte' => $troupes.' troupe(s) d\'assaut : '.$attaque.' de force', 'valeurs' => array('troupes' => $garnison))),
            'bilan' => $message,
        ));
        return null;
    }

    private static function infliger(int $degats, int $principal, int $secondaire): array {
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

    private static function alea(int $force): int {
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
