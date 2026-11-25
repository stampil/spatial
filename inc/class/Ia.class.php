<?php

class Ia {

    const INTERVALLE_SECONDES = 12 * Planete::SECONDS_PER_HOUR;
    const PROTECTION_SECONDES = Joueur::VISIBILITE_APRES_TUTO_SECONDES;
    const PUISSANCE_PAR_SLOT = 4;
    const REVOLTE_EMPIRE = 25;
    const PERCENT = 100;
    const CAMPS_RAIDEURS = array(Camp::PIRATES, Camp::EMPIRE);

    public static function resoudre(Joueur $joueur): void {
        if($joueur->enTuto() || $joueur->fin_tuto === null || strtotime($joueur->fin_tuto) + self::PROTECTION_SECONDES > time()){
            return;
        }
        $db = MyPDO::getInstance();
        if($joueur->raid_maj === null){
            $db->query("UPDATE " . MyPDO::DB_FLAG . "joueur SET raid_maj=? WHERE id=? AND raid_maj IS NULL",Tools::now(),$joueur->id);
            return;
        }
        if(time() - strtotime($joueur->raid_maj) < self::INTERVALLE_SECONDES){
            return;
        }
        $db->query("UPDATE " . MyPDO::DB_FLAG . "joueur SET raid_maj=? WHERE id=? AND raid_maj=?",Tools::now(),$joueur->id,$joueur->raid_maj);
        if($db->lastRowCount !== 1){
            return;
        }
        self::raid($joueur);
    }

    public static function raid(Joueur $joueur): void {
        $cibles = Planete::listeGouvernees($joueur->id);
        if(count($cibles) === 0){
            return;
        }
        $planete = new Planete($cibles[random_int(0, count($cibles) - 1)]->id);
        $source = self::sourceLaPlusProche($joueur, $planete);
        if($source === null){
            return;
        }
        if((int) $source->id_camp === Camp::PIRATES){
            Menace::creer($joueur, $planete, $source);
            return;
        }
        $camp = new Camp($source->id_camp);
        $puissance = Combat::alea($source->slot * self::PUISSANCE_PAR_SLOT);
        $navires = Vaisseau::escadre($planete->id, $joueur->id);
        $resistance = $planete->bonus === Bonus::CITADELLE ? Bonus::pourcent(Bonus::CITADELLE) : 0;
        $defense = intdiv(($planete->canon + Combat::forceTotale($navires)) * (self::PERCENT + $resistance), self::PERCENT);
        $etapes = array(array(
            'texte' => 'Flotte '.$camp->nom.' partie de '.$source->nom.' : puissance '.$puissance.' contre une défense de '.$defense,
            'valeurs' => array('bouclier' => $planete->bouclier, 'canon' => $planete->canon),
        ));
        if($defense >= $puissance){
            $bilan = 'Raid '.$camp->nom.' repoussé sur '.$planete->nom.' : la défense a tenu.';
        }
        else{
            $degats = $puissance - $defense;
            list($bouclier, $canon) = Combat::infliger($degats, $planete->bouclier, $planete->canon);
            $query = "UPDATE " . MyPDO::DB_FLAG . "planete SET bouclier=?, canon=?, defense_maj=COALESCE(defense_maj, ?) WHERE id=?";
            MyPDO::getInstance()->query($query,$bouclier,$canon,Tools::now(),$planete->id);
            $bilan = 'Raid '.$camp->nom.' sur '.$planete->nom.' : défense restante, bouclier '.$bouclier.' et canon '.$canon.'.';
            $reste = max(0, $degats - $planete->bouclier - $planete->canon);
            $pertes = Combat::detruire($navires, $reste);
            if(array_sum($pertes) > 0){
                Vaisseau::detruire($planete->id, $joueur->id, $pertes);
                $bilan .= ' Vaisseaux détruits : '.array_sum($pertes).'.';
            }
            if($bouclier + $canon === 0){
                $bilan .= self::agiter($planete);
            }
            array_push($etapes, array('texte' => 'Dégâts infligés : '.$degats, 'valeurs' => array('bouclier' => $bouclier, 'canon' => $canon)));
        }
        Tools::addMessage($bilan);
        Tools::addRapport(array(
            'titre' => 'Raid '.$camp->nom.' sur '.$planete->nom,
            'barres' => array(
                array('cle' => 'bouclier', 'nom' => 'Bouclier', 'maximum' => max(Planete::bouclierDeBase($planete->slot), $planete->bouclier)),
                array('cle' => 'canon', 'nom' => 'Canon', 'maximum' => max(Planete::canonDeBase($planete->slot), $planete->canon)),
            ),
            'etapes' => $etapes,
            'bilan' => $bilan,
        ));
    }

    private static function sourceLaPlusProche(Joueur $joueur, Planete $cible): ?object {
        $marques = implode(',', array_fill(0, count(self::CAMPS_RAIDEURS), '?'));
        $query = "SELECT p.id, p.nom, p.slot, p.id_camp FROM " . MyPDO::DB_FLAG . "planete p JOIN " . MyPDO::DB_FLAG . "systeme s ON s.id=p.id_systeme
            WHERE p.gouverneur=0 AND p.id_camp IN ($marques) AND p.id_camp<>? AND p.bouclier+p.canon>0
            ORDER BY (s.x-?)*(s.x-?)+(s.y-?)*(s.y-?), p.id LIMIT 1";
        $x = $cible->systeme->x;
        $y = $cible->systeme->y;
        $ret = MyPDO::getInstance()->query($query,...array_merge(self::CAMPS_RAIDEURS, array($joueur->id_camp, $x, $x, $y, $y)));
        return $ret[0] ?? null;
    }

    private static function agiter(Planete $planete): string {
        $query = "UPDATE " . MyPDO::DB_FLAG . "planete SET perc_revolte=LEAST(perc_revolte+?, ?) WHERE id=?";
        MyPDO::getInstance()->query($query,self::REVOLTE_EMPIRE,Planete::MAX_REVOLTE - 1,$planete->id);
        return ' Défense détruite : la révolte monte de '.self::REVOLTE_EMPIRE.' points.';
    }

}
