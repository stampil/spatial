<?php

class Planet{
    private $manager;
    public function __construct() {
        $this->manager = PlanetManager::getInstance();
        echo "c'est l'été";
    }
    
    public function listPlanet($id_planet){
        return $this->manager->getPlanet($id_planet);
    }
}

