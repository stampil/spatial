<?php

class Systeme {

    private $bdd;
    public $id;
    public $nom;
    public $planetes;

        
    public static function liste(){
        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "systeme ";
        $ret = MyPDO::getInstance()->query($query);
        return $ret;
    }

    /**
     * __construct
     * @param type $id
     * @throws UnexpectedValueException
     */
    public function __construct($id) {
        $this->bdd = MyPDO::getInstance();
        $this->load($id);
    }

    private function load($id) {
        
        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "systeme
                WHERE id=? ";
        $ret = $this->bdd->query($query, $id);
        
        $this->nom = $ret[0]->nom;
        $this->id = $ret[0]->id;
        $this->planetes = Planete::liste($this->id);
        return $this;
    }
    
    public function save(){     
        $query ="UPDATE " . MyPDO::DB_FLAG . "systeme 
            set
            nom = ?           
            WHERE id =?";
        $ret = $this->bdd->query($query,          
                $this->nom,                
                $this->id
                );
    }
    
}
