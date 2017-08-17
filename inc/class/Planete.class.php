<?php

class Planete {

    private $bdd;
    public $id;
    public $nom;
    public $slot;
    public $perc_revolte;
    public $id_systeme;
    public $nb_usine_vaiss_leger;
    public $nb_usine_vaiss_moyen;
    public $nb_usine_vaiss_lourd;
    public $nb_usine_ressource;
    public $nb_academie;
    public $systeme;
        
    public static function liste(){
        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "planete ";
        $ret = MyPDO::getInstance()->query($query);
        return $ret;
    }
    
    public static function fillSysteme($id_systeme){
        $nb_planetes = rand(3, 12);
        for ($i=0; $i<$nb_planetes; $i++){
            $query = "INSERT INTO " . MyPDO::DB_FLAG . "planete(nom,perc_revolte,slot,gouverneur,id_systeme,nb_usine_vaiss_leger,nb_usine_vaiss_moyen,nb_usine_vaiss_lourd,nb_usine_ressource,nb_academie)
                VALUES (?,?,?,?,?,?,?,?,?,?);";
            $gouverneur=0;
            $slot =rand(1,16);
            if($i==0){//premiere planete créer : base du joueur
                $gouverneur = $id_systeme; // = $id_joueur
                $slot = 8; //meme base de depart pour tout les joueurs et permettre une bonne expension
            }
            MyPDO::getInstance()->query($query, '',rand(0,25),$slot,$gouverneur,$id_systeme,0,0,0,0,0);
        }
    }

    /**
     * __construct
     * @param type $id_planet
     * @throws UnexpectedValueException
     */
    public function __construct($id) {
        $this->bdd = MyPDO::getInstance();
        $this->load($id);
    }

    private function load($id) {

        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "planete
                WHERE id=? ";
        $ret = $this->bdd->query($query, $id);
        
        $this->nom = $ret[0]->nom;
        $this->id = $ret[0]->id;
        $this->perc_revolte = $ret[0]->perc_revolte;
        $this->slot = $ret[0]->slot;
        $this->gouverneur = $ret[0]->gouverneur;
        $this->id_systeme = $ret[0]->id_systeme;
        $this->nb_usine_vaiss_leger = $ret[0]->nb_usine_vaiss_leger;
        $this->nb_usine_vaiss_moyen = $ret[0]->nb_usine_vaiss_moyen;
        $this->nb_usine_vaiss_lourd = $ret[0]->nb_usine_vaiss_lourd;
        $this->nb_usine_ressource = $ret[0]->nb_usine_ressource;
        $this->nb_academie = $ret[0]->nb_academie;
        $this->systeme = new Systeme($this->id_systeme);
        return $this;

    }
    
    public function save(){     
        $query ="UPDATE " . MyPDO::DB_FLAG . "planete 
            set
            nom = ?,
            perc_revolte = ?,
            slot = ?,
            gouverneur = ?,
            id_systeme = ?,
            nb_usine_vaiss_leger = ?,
            nb_usine_vaiss_moyen = ?,
            nb_usine_vaiss_lourd = ?,
            nb_usine_ressource = ?,
            nb_academie = ?
            WHERE id =?";
        $ret = $this->bdd->query($query,          
                $this->nom,
                $this->perc_revolte,
                $this->slot,
                $this->gouverneur,
                $this->id_systeme,
                $this->nb_usine_vaiss_leger,
                $this->nb_usine_vaiss_moyen,
                $this->nb_usine_vaiss_lourd,
                $this->nb_usine_ressource,
                $this->nb_academie,
                $this->id
                );
    }
    
}
