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
    const DIPLOMATE_PRIX = 100;
    const CATALOGUE = array(
        self::USINE_LEGERE => array('nom' => 'Usine de vaisseaux légers', 'prix' => 100, 'maintenance' => 2),
        self::USINE_MOYENNE => array('nom' => 'Usine de vaisseaux moyens', 'prix' => 200, 'maintenance' => 4),
        self::USINE_LOURDE => array('nom' => 'Usine de vaisseaux lourds', 'prix' => 400, 'maintenance' => 8),
        self::FERME => array('nom' => 'Ferme', 'prix' => 150, 'maintenance' => 3),
        self::ACADEMIE => array('nom' => 'Académie', 'prix' => 250, 'maintenance' => 5),
    );

    public static function remboursement(string $batiment): int {
        return intdiv(self::CATALOGUE[$batiment]['prix'] * self::REFUND_PERCENT, self::MAX_PERCENT);
    }

}
