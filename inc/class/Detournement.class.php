<?php

class Detournement {

    const DUREE_HEURES = 6;
    const POURCENT = 50;
    const PERCENT = 100;

    public static function appliquer(Joueur $victime, Planete $planete): void {
        $montant = min($victime->credits, intdiv(max(0, $planete->revenuNet()) * self::DUREE_HEURES * self::POURCENT, self::PERCENT));
        if($montant < 1){
            Tools::addMessage('Les Pirates repartent bredouilles : '.$planete->nom.' ne leur rapporte rien.');
            return;
        }
        $db = MyPDO::getInstance();
        $query = "UPDATE " . MyPDO::DB_FLAG . "planete SET detournement_fin=? WHERE id=? AND (detournement_fin IS NULL OR detournement_fin<=?)";
        $db->query($query,Tools::nowPlus(self::DUREE_HEURES * Planete::SECONDS_PER_HOUR),$planete->id,Tools::now());
        if($db->lastRowCount !== 1){
            Tools::addMessage('Les revenus de '.$planete->nom.' sont déjà détournés : les Pirates ne prélèvent rien de plus.');
            return;
        }
        if(!$victime->payer($montant)){
            $db->query("UPDATE " . MyPDO::DB_FLAG . "planete SET detournement_fin=NULL WHERE id=?",$planete->id);
            return;
        }
        Tools::addMessage('Les Pirates détournent '.$montant.' cr des revenus de '.$planete->nom.' pendant '.self::DUREE_HEURES.' h, sans conquérir la planète.');
    }

}
