<?php

class Detournement {

    const DUREE_HEURES = 6;
    const POURCENT = 50;
    const PERCENT = 100;

    public static function lancer(Joueur $pirate, Planete $planete): ?string {
        if($pirate->id_camp !== Camp::PIRATES){
            return 'Seuls les Pirates détournent des ressources.';
        }
        if($planete->gouverneur === 0){
            return 'Cette planète ne produit aucune ressource à détourner.';
        }
        if($planete->gouverneur === $pirate->id){
            return 'Vous gouvernez déjà cette planète.';
        }
        if(!$planete->systeme->estConnuDe($pirate->id)){
            return 'Cette planète est inconnue.';
        }
        $victime = new Joueur($planete->gouverneur);
        if($victime->enTuto()){
            return 'Ce gouverneur est encore en tutoriel.';
        }
        if(array_sum(Vaisseau::escadre($planete->id, $pirate->id)) === 0){
            return 'Aucun vaisseau de combat sur cette planète.';
        }
        if($planete->bouclier + $planete->canon > 0){
            return "La défense planétaire tient encore : détruisez-la d'abord.";
        }
        $revenu = $planete->revenuNet();
        if($revenu < 1){
            return 'Cette planète ne rapporte rien.';
        }
        $montant = min($victime->credits, intdiv($revenu * self::DUREE_HEURES * self::POURCENT, self::PERCENT));
        if($montant < 1){
            return "Le gouverneur n'a plus de crédits à détourner.";
        }
        $db = MyPDO::getInstance();
        $query = "UPDATE " . MyPDO::DB_FLAG . "planete SET detournement_fin=? WHERE id=? AND (detournement_fin IS NULL OR detournement_fin<=?)";
        $db->query($query,Tools::nowPlus(self::DUREE_HEURES * Planete::SECONDS_PER_HOUR),$planete->id,Tools::now());
        if($db->lastRowCount !== 1){
            return 'Les revenus de cette planète ont déjà été détournés : attendez la fin du délai.';
        }
        if(!$victime->payer($montant)){
            $db->query("UPDATE " . MyPDO::DB_FLAG . "planete SET detournement_fin=NULL WHERE id=?",$planete->id);
            return 'La situation a changé : recommencez.';
        }
        $pirate->crediter($montant);
        Journal::ajouter($victime->id, 'Les Pirates de '.$pirate->nom.' ont détourné '.$montant.' cr des revenus de '.$planete->nom.'.');
        Tools::addMessage('Revenus de '.$planete->nom.' détournés : +'.$montant.' cr. Prochain détournement possible dans '.self::DUREE_HEURES.' h.');
        return null;
    }

}
