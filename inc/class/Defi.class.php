<?php

class Defi {

    const EXPIRATION_SECONDES = 24 * Planete::SECONDS_PER_HOUR;

    public static function purger(): void {
        $query = "DELETE FROM " . MyPDO::DB_FLAG . "defi WHERE creato<?";
        MyPDO::getInstance()->query($query,Tools::formatDate(time() - self::EXPIRATION_SECONDES));
    }

    public static function adversaires(Joueur $joueur, Planete $planete): array {
        $marques = implode(',', array_fill(0, count(Combat::ESCADRE), '?'));
        $query = "SELECT DISTINCT v.id_joueur FROM " . MyPDO::DB_FLAG . "vaisseau v JOIN " . MyPDO::DB_FLAG . "joueur j ON j.id=v.id_joueur
            WHERE v.id_planete=? AND v.arrivee IS NULL AND v.type IN ($marques) AND j.id_camp=? AND v.id_joueur<>? ORDER BY v.id_joueur";
        $adversaires = array();
        foreach(MyPDO::getInstance()->query($query,...array_merge(array($planete->id), Combat::ESCADRE, array(Camp::PIRATES, $joueur->id))) as $ligne){
            array_push($adversaires, new Joueur($ligne->id_joueur));
        }
        return $adversaires;
    }

    public static function recus(int $joueurId): array {
        $query = "SELECT * FROM " . MyPDO::DB_FLAG . "defi WHERE id_defenseur=? ORDER BY id";
        return MyPDO::getInstance()->query($query,$joueurId);
    }

    public static function envoyes(int $joueurId): array {
        $query = "SELECT * FROM " . MyPDO::DB_FLAG . "defi WHERE id_attaquant=? ORDER BY id";
        return MyPDO::getInstance()->query($query,$joueurId);
    }

    public static function lancer(Joueur $attaquant, Planete $planete, int $defenseurId): ?string {
        if($attaquant->id_camp !== Camp::PIRATES){
            return 'Seuls les Pirates se défient entre eux.';
        }
        if($defenseurId === $attaquant->id){
            return 'Vous ne pouvez pas vous défier vous-même.';
        }
        $defenseur = new Joueur($defenseurId);
        if($defenseur->id_camp !== Camp::PIRATES){
            return "Ce joueur n'est pas un Pirate.";
        }
        if($attaquant->enTuto() || $defenseur->enTuto()){
            return 'Un joueur en tutoriel ne peut pas être défié.';
        }
        if(array_sum(Vaisseau::escadre($planete->id, $attaquant->id)) === 0){
            return 'Aucun vaisseau de combat à vous sur cette planète.';
        }
        if(array_sum(Vaisseau::escadre($planete->id, $defenseur->id)) === 0){
            return "Votre adversaire n'a aucun vaisseau de combat sur cette planète.";
        }
        $db = MyPDO::getInstance();
        $query = "INSERT IGNORE INTO " . MyPDO::DB_FLAG . "defi (id_attaquant,id_defenseur,id_planete,creato) VALUES (?,?,?,?)";
        $db->query($query,$attaquant->id,$defenseur->id,$planete->id,Tools::now());
        if($db->lastRowCount !== 1){
            return 'Un défi est déjà en cours contre ce joueur sur cette planète.';
        }
        Journal::ajouter($defenseur->id, $attaquant->nom.' vous défie sur '.$planete->nom.' : acceptez ou refusez depuis la page Flottes.');
        Tools::addMessage('Défi envoyé à '.$defenseur->nom.' : il peut l\'accepter pendant '.intdiv(self::EXPIRATION_SECONDES, Planete::SECONDS_PER_HOUR).' h.');
        return null;
    }

    public static function repondre(Joueur $defenseur, int $defiId, bool $accepte): ?string {
        $db = MyPDO::getInstance();
        $ret = $db->query("SELECT * FROM " . MyPDO::DB_FLAG . "defi WHERE id=? AND id_defenseur=?",$defiId,$defenseur->id);
        if(empty($ret)){
            return "Ce défi n'existe plus.";
        }
        $db->query("DELETE FROM " . MyPDO::DB_FLAG . "defi WHERE id=? AND id_defenseur=?",$defiId,$defenseur->id);
        if($db->lastRowCount !== 1){
            return "Ce défi n'existe plus.";
        }
        $attaquant = new Joueur($ret[0]->id_attaquant);
        $planete = new Planete($ret[0]->id_planete);
        if(!$accepte){
            Journal::ajouter($attaquant->id, $defenseur->nom.' a refusé votre défi sur '.$planete->nom.'.');
            Tools::addMessage('Défi de '.$attaquant->nom.' refusé.');
            return null;
        }
        $flottes = array(
            Combat::ATTAQUANT => Vaisseau::escadre($planete->id, $attaquant->id),
            Combat::DEFENSEUR => Vaisseau::escadre($planete->id, $defenseur->id),
        );
        if(array_sum($flottes[Combat::ATTAQUANT]) === 0 || array_sum($flottes[Combat::DEFENSEUR]) === 0){
            return "Le combat n'a pas lieu : un camp n'a plus de vaisseau de combat sur cette planète.";
        }
        $bataille = Combat::bataille('Combat pirate sur '.$planete->nom, array(
            Combat::ATTAQUANT => array('nom' => $attaquant->nom, 'nombres' => $flottes[Combat::ATTAQUANT], 'bonus' => self::bonusHeros($attaquant, $planete)),
            Combat::DEFENSEUR => array('nom' => $defenseur->nom, 'nombres' => $flottes[Combat::DEFENSEUR], 'bonus' => self::bonusHeros($defenseur, $planete)),
        ));
        Vaisseau::detruire($planete->id, $attaquant->id, $bataille['pertes'][Combat::ATTAQUANT]);
        Vaisseau::detruire($planete->id, $defenseur->id, $bataille['pertes'][Combat::DEFENSEUR]);
        if($bataille['vainqueur'] === Combat::ATTAQUANT){
            $attaquant->crediter($bataille['epaves']);
        }
        elseif($bataille['vainqueur'] === Combat::DEFENSEUR){
            $defenseur->crediter($bataille['epaves']);
        }
        Journal::ajouter($attaquant->id, 'Défi accepté sur '.$planete->nom.' : '.$bataille['bilan']);
        Tools::addMessage($bataille['bilan']);
        Tools::addRapport($bataille['rapport']);
        return null;
    }

    private static function bonusHeros(Joueur $joueur, Planete $planete): int {
        return Perso::boostHeros($joueur, $planete, Perso::CARACTERISTIQUE_FORCE) * Combat::HEROS_BONUS_PAR_ETOILE_POURCENT;
    }

}
