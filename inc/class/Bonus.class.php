<?php

class Bonus {

    const CHANTIER = 'chantier';
    const FORGE = 'forge';
    const MARCHE = 'marche';
    const CITADELLE = 'citadelle';
    const RELAIS = 'relais';
    const PERCENT = 100;
    const CATALOGUE = array(
        self::CHANTIER => array('nom' => 'Chantier', 'pourcent' => 25, 'description' => 'Vaisseaux capitaux à -%d %% pour son gouverneur.'),
        self::FORGE => array('nom' => 'Forge de chasse', 'pourcent' => 25, 'description' => 'Chasseurs à -%d %% pour son gouverneur.'),
        self::MARCHE => array('nom' => 'Marché', 'pourcent' => 20, 'description' => 'Revenu du gouverneur à +%d %% (revenu positif).'),
        self::CITADELLE => array('nom' => 'Citadelle', 'pourcent' => 25, 'description' => 'Défense de la planète à +%d %% face aux attaques.'),
        self::RELAIS => array('nom' => 'Relais', 'pourcent' => 25, 'description' => 'Trajets à -%d %% au départ de cette planète pour son gouverneur.'),
    );
    const REMISES_VAISSEAU = array(self::CHANTIER => 'capital', self::FORGE => 'chasseur');

    public static function tirer(): string {
        $bonus = array_keys(self::CATALOGUE);
        return $bonus[random_int(0, count($bonus) - 1)];
    }

    public static function pourcent(string $bonus): int {
        return self::CATALOGUE[$bonus]['pourcent'];
    }

    public static function libelle(?string $bonus): string {
        if($bonus === null){
            return '';
        }
        return self::CATALOGUE[$bonus]['nom'];
    }

    public static function description(string $bonus): string {
        return sprintf(self::CATALOGUE[$bonus]['description'], self::pourcent($bonus));
    }

}
