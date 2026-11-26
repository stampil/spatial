<?php

class Batiment {

    const USINE_LEGERE = 'nb_usine_vaiss_leger';
    const USINE_MOYENNE = 'nb_usine_vaiss_moyen';
    const USINE_LOURDE = 'nb_usine_vaiss_lourd';
    const FERME = 'nb_usine_ressource';
    const ACADEMIE = 'nb_academie';
    const BOUCLIER_PLANETAIRE = 'nb_bouclier_planetaire';
    const CANON_ORBITAL = 'nb_canon_orbital';
    const TROUPES_PAR_ACADEMIE = 10;
    const BASE_INCOME = 10;
    const FERME_INCOME = 25;
    const REFUND_PERCENT = 50;
    const MAX_PERCENT = 100;
    const CATALOGUE = array(
        self::USINE_LEGERE => array('nom' => 'Usine de vaisseaux légers', 'court' => 'Usine légère', 'icone' => 'usine_legere', 'prix' => 100, 'maintenance' => 2),
        self::USINE_MOYENNE => array('nom' => 'Usine de vaisseaux moyens', 'court' => 'Usine moyenne', 'icone' => 'usine_moyenne', 'prix' => 200, 'maintenance' => 4),
        self::USINE_LOURDE => array('nom' => 'Usine de vaisseaux lourds', 'court' => 'Usine lourde', 'icone' => 'usine_lourde', 'prix' => 400, 'maintenance' => 8),
        self::FERME => array('nom' => 'Ferme', 'court' => 'Ferme', 'icone' => 'ferme', 'prix' => 150, 'maintenance' => 3),
        self::ACADEMIE => array('nom' => 'Académie', 'court' => 'Académie', 'icone' => 'academie', 'prix' => 250, 'maintenance' => 5),
        self::BOUCLIER_PLANETAIRE => array('nom' => 'Bouclier planétaire', 'court' => 'Bouclier', 'icone' => 'bouclier', 'prix' => 300, 'maintenance' => 6),
        self::CANON_ORBITAL => array('nom' => 'Canon orbital', 'court' => 'Canon', 'icone' => 'canon', 'prix' => 300, 'maintenance' => 6),
    );

    public static function description(string $batiment): string {
        if($batiment === self::FERME){
            return 'Rapporte '.self::FERME_INCOME.' cr/h.';
        }
        if($batiment === self::BOUCLIER_PLANETAIRE){
            return 'Ajoute +'.Planete::BOUCLIER_PAR_STRUCTURE.' de bouclier maximum. Tant qu\'il en reste un, aucune troupe ne peut débarquer.';
        }
        if($batiment === self::CANON_ORBITAL){
            return 'Ajoute +'.Planete::CANON_PAR_STRUCTURE.' de canon maximum : la planète renvoie plus de dégâts aux attaquants.';
        }
        $noms = array();
        foreach(Vaisseau::typesDeLUsine($batiment) as $type){
            array_push($noms, Vaisseau::CATALOGUE[$type]['nom']);
        }
        $texte = 'Produit : '.implode(', ', $noms).'.';
        if($batiment === self::ACADEMIE){
            $roles = array();
            foreach(Perso::ROLES as $infosRole){
                array_push($roles, $infosRole['nom']);
            }
            $texte = 'Recrute des persos ('.implode(', ', $roles).'). '.$texte.' Chaque académie autorise '.self::TROUPES_PAR_ACADEMIE.' '.__('troupe_presente', 'troupes_presentes', self::TROUPES_PAR_ACADEMIE).' sur la planète.';
        }
        return $texte;
    }

    public static function capaciteTroupes(Planete $planete): int {
        return $planete->nombre(self::ACADEMIE) * self::TROUPES_PAR_ACADEMIE;
    }

    public static function remboursement(string $batiment): int {
        return intdiv(self::CATALOGUE[$batiment]['prix'] * self::REFUND_PERCENT, self::MAX_PERCENT);
    }

}
