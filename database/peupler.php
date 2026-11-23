<?php
// Run once at game setup: php database/peupler.php

if(PHP_SAPI !== 'cli'){
    exit('Script en ligne de commande uniquement.');
}

chdir(__DIR__ . '/..');
require 'inc/const.php';
require 'inc/functions.php';

$plan = array(
    array('nombre' => 6, 'camps' => array(Camp::EMPIRE)),
    array('nombre' => 3, 'camps' => array(Camp::PIRATES)),
    array('nombre' => 3, 'camps' => array(Camp::EMPIRE, Camp::PIRATES, null)),
    array('nombre' => 4, 'camps' => array(null)),
);

$query = "SELECT COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "planete WHERE id_camp IS NOT NULL AND gouverneur=0";
$ret = MyPDO::getInstance()->query($query);
if(!empty($ret) && $ret[0]->nombre > 0){
    exit("Le jeu est déjà peuplé.\n");
}

foreach($plan as $etape){
    for($i=0; $i<$etape['nombre']; $i++){
        $Systeme = new Systeme(Systeme::create());
        Planete::fillSystemeIA($Systeme, $etape['camps']);
        echo 'Système '.$Systeme->nom." créé\n";
    }
}
