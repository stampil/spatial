<?php

class Batiment {

    const USINE_LEGERE = 'nb_usine_vaiss_leger';
    const USINE_MOYENNE = 'nb_usine_vaiss_moyen';
    const USINE_LOURDE = 'nb_usine_vaiss_lourd';
    const FERME = 'nb_usine_ressource';
    const ACADEMIE = 'nb_academie';
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
    );

    public static function description(string $batiment): string {
        if($batiment === self::FERME){
            return 'Rapporte '.self::FERME_INCOME.' cr/h.';
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
            $texte = 'Recrute des persos ('.implode(', ', $roles).'). '.$texte;
        }
        return $texte;
    }

    public static function remboursement(string $batiment): int {
        return intdiv(self::CATALOGUE[$batiment]['prix'] * self::REFUND_PERCENT, self::MAX_PERCENT);
    }

}
