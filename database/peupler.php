<?php
// Run once at game setup: php database/peupler.php

if(PHP_SAPI !== 'cli'){
    exit('Script en ligne de commande uniquement.');
}

chdir(__DIR__ . '/..');
require 'inc/const.php';
require 'inc/functions.php';

if(Univers::estPeuple()){
    exit("Le jeu est déjà peuplé.\n");
}

foreach(Univers::peupler() as $nom){
    echo 'Système '.$nom." créé\n";
}
