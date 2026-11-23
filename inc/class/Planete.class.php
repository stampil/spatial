<?php

class Planete {

    private MyPDO $bdd;
    public int $id;
    public string $nom;
    public int $slot;
    public int $perc_revolte;
    public int $gouverneur;
    public int $id_systeme;
    public int $nb_usine_vaiss_leger;
    public int $nb_usine_vaiss_moyen;
    public int $nb_usine_vaiss_lourd;
    public int $nb_usine_ressource;
    public int $nb_academie;
    public Systeme $systeme;
    public static int $min_slot = 1;
    public static int $max_slot = 16;
    
        
    public static function liste(?int $id_systeme=null): array{
        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "planete ";
        if($id_systeme){
            $query .= " WHERE id_systeme=?";
            return MyPDO::getInstance()->query($query,$id_systeme);
        }
        return MyPDO::getInstance()->query($query);
    }
    
    
    public static function fillSysteme(Perso $Perso,Systeme $Systeme): void{
        $nb_planetes = rand(3, 12);
        
        for ($i=1; $i<=$nb_planetes; $i++){
            $nom_planete = preg_replace("/-[0-9]+/",' '.$i,$Systeme->nom);
            
            $query = "INSERT INTO " . MyPDO::DB_FLAG . "planete(nom,perc_revolte,slot,gouverneur,id_systeme,nb_usine_vaiss_leger,nb_usine_vaiss_moyen,nb_usine_vaiss_lourd,nb_usine_ressource,nb_academie,creato)
                VALUES (?,?,?,?,?,?,?,?,?,?,now());";
            $gouverneur=0;
            $slot =rand(self::$min_slot,self::$max_slot);
            if($i==1){//premiere planete créer : base du joueur
                //$gouverneur = $Joueur->id; // = $id_joueur pas encore : tuto fera devenir gouverneur
                $slot = 8; //meme base de depart pour tout les joueurs et permettre une bonne expension
            }
            MyPDO::getInstance()->query($query, $nom_planete,rand(0,25),$slot,$gouverneur,$Systeme->id,0,0,0,0,0);
            if($i==1){
                $id_planete =  MyPDO::getInstance()->lastInsertId();
                $Planete = new Planete($id_planete);
                $Perso->goToPlanete($Planete);
            }
            
        }
    }

    /**
     * __construct
     * @param int $id
     * @throws UnexpectedValueException
     */
    public function __construct(int $id) {
        $this->bdd = MyPDO::getInstance();
        $this->load($id);
    }

    private function load(int $id): static {

        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "planete
                WHERE id=? ";
        $ret = $this->bdd->query($query, $id);
        if(empty($ret)){
            throw new UnexpectedValueException("Planete n'existe pas");
        }
        
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
    
    public function save(): void {     
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

public function getNom(): string {
    return '<a target="_blank" href="?p=affichePlanete&id_planete='.$this->id.'" class="nomPlanete">'.htmlspecialchars($this->nom).'</a>';
}
    
}
