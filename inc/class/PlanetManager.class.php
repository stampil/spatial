<?php

class PlanetManager{
    private $bdd;
    private static $_instance = null;
    
    public function __construct() {
        if(!self::$_instance){
                die(__CLASS__.' must call : PlanetManager::getInstance() !');
        }
        
        $this->bdd = MyPDO::getInstance();

    }
    
    public static function getInstance() {
 
        if(is_null(self::$_instance)) {
          self::$_instance =true; //force $_instance set temporaly with true for pass the __construct step
          self::$_instance = new PlanetManager();  
        }

        return self::$_instance;
    }
    
    public function getPlanet($id){
        $query="SELECT *
                FROM ".MyPDO::DB_FLAG."planet
                WHERE id_planet=? "; 
        $ret = $this->bdd->query($query,$id);
        return @$ret[0];
    }
}

