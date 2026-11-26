<?php

class Menace {

    const EXPIRATION_SECONDES = 24 * Planete::SECONDS_PER_HOUR;
    const CHASSEURS_DIVISEUR_SLOT = 2;
    const CHASSEURS_MINIMUM = 1;
    const FREGATES_DIVISEUR_SLOT = 4;
    const CAPITAUX_DIVISEUR_SLOT = 12;
    const ACTION_COMBATTRE = 'combattre';
    const ACTION_LAISSER = 'laisser';

    public static function flotte(int $slot): array {
        return array_combine(Combat::ESCADRE, array(
            intdiv($slot, self::CHASSEURS_DIVISEUR_SLOT) + self::CHASSEURS_MINIMUM,
            intdiv($slot, self::FREGATES_DIVISEUR_SLOT),
            intdiv($slot, self::CAPITAUX_DIVISEUR_SLOT),
        ));
    }

    public static function enCours(int $joueurId): ?object {
        $ret = MyPDO::getInstance()->query("SELECT * FROM " . MyPDO::DB_FLAG . "menace WHERE id_joueur=?",$joueurId);
        return $ret[0] ?? null;
    }

    public static function creer(Joueur $joueur, Planete $planete, object $source): void {
        $db = MyPDO::getInstance();
        $query = "INSERT IGNORE INTO " . MyPDO::DB_FLAG . "menace (id_joueur,id_planete,id_source,creato) VALUES (?,?,?,?)";
        $db->query($query,$joueur->id,$planete->id,$source->id,Tools::now());
        if($db->lastRowCount !== 1){
            return;
        }
        Tools::addMessage('Une flotte pirate de '.array_sum(self::flotte($source->slot)).' '.__('vaisseau','vaisseaux',array_sum(self::flotte($source->slot))).', venue de '.$source->nom.', menace '.$planete->nom.' : combattez-la ou laissez-la détourner vos revenus (réponse depuis la page Flottes, sous '.intdiv(self::EXPIRATION_SECONDES, Planete::SECONDS_PER_HOUR).' h).');
    }

    public static function expirer(Joueur $joueur): void {
        $menace = self::enCours($joueur->id);
        if($menace === null || strtotime($menace->creato) + self::EXPIRATION_SECONDES > time()){
            return;
        }
        self::repondre($joueur, $menace->id, false);
    }

    public static function repondre(Joueur $joueur, int $menaceId, bool $combat): ?string {
        $db = MyPDO::getInstance();
        $ret = $db->query("SELECT * FROM " . MyPDO::DB_FLAG . "menace WHERE id=? AND id_joueur=?",$menaceId,$joueur->id);
        if(empty($ret)){
            return "Cette menace n'existe plus.";
        }
        $db->query("DELETE FROM " . MyPDO::DB_FLAG . "menace WHERE id=? AND id_joueur=?",$menaceId,$joueur->id);
        if($db->lastRowCount !== 1){
            return "Cette menace n'existe plus.";
        }
        $planete = new Planete($ret[0]->id_planete);
        if($planete->gouverneur !== $joueur->id){
            return 'La planète menacée ne vous appartient plus : la menace est levée.';
        }
        if($combat){
            self::combattre($joueur, $planete, new Planete($ret[0]->id_source));
        }
        else{
            Detournement::appliquer($joueur, $planete);
        }
        return null;
    }

    private static function combattre(Joueur $joueur, Planete $planete, Planete $source): void {
        $bataille = Combat::bataille('Menace pirate sur '.$planete->nom, array(
            Combat::ATTAQUANT => array('nom' => (new Camp(Camp::PIRATES))->nom, 'nombres' => self::flotte($source->slot), 'bonus' => 0),
            Combat::DEFENSEUR => array('nom' => $joueur->nom, 'nombres' => Vaisseau::escadre($planete->id, $joueur->id), 'bonus' => self::bonusDefense($joueur, $planete)),
        ));
        Vaisseau::detruire($planete->id, $joueur->id, $bataille['pertes'][Combat::DEFENSEUR]);
        if($bataille['vainqueur'] === Combat::DEFENSEUR){
            $joueur->crediter($bataille['epaves']);
        }
        Tools::addMessage($bataille['bilan']);
        Tools::addRapport($bataille['rapport']);
        if($bataille['vainqueur'] === Combat::ATTAQUANT){
            Detournement::appliquer($joueur, $planete);
        }
    }

    public static function bonusDefense(Joueur $joueur, Planete $planete): int {
        $bonus = Perso::boostHeros($joueur, $planete, Perso::CARACTERISTIQUE_FORCE) * Combat::HEROS_BONUS_PAR_ETOILE_POURCENT;
        if($planete->bonus === Bonus::CITADELLE){
            $bonus += Bonus::pourcent(Bonus::CITADELLE);
        }
        return $bonus;
    }

}
