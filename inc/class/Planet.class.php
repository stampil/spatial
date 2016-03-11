<?php

class Planet{
    private $manager;
    private $id_planet;
    private $nom;
    private $x;
    private $y;
    
    /**
     * __construct
     * @param type $id_planet
     * @throws UnexpectedValueException
     */
    public function __construct($id_planet) {
        if(!is_integer($id_planet)){
            $err = __CLASS__.' param id_planet must be int';
            error_log($err);
            throw new UnexpectedValueException($err);
        }
        $this->manager = PlanetManager::getInstance();
        $this->HydratePlanet($this->manager->getPlanet($id_planet));
    }
    
    /**
     * HydratePlanet
     * @param stdClass $array
     */
    private function HydratePlanet(stdClass $array){
        foreach($array as $nomColonne => $value){
            $this->$nomColonne = $value;
        }
    }
    
    /**
     * get_nom
     * @return type
     */
    public function get_nom(){
        return $this->nom;
    }

}