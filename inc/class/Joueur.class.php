<?php

class Joueur {

    private MyPDO $bdd;
    private string $email;
    private string $mdp;
    
    public string $creato;
    public string $lastco;
    public int $id;
    public string $nom;
    public int $credits;
    public int $etape_tuto;

    public string $IPs;
    
    private static int $credits_depart = 500;
    

    
    public static function inscrire(array $infos): ?Joueur{
        $query = "INSERT INTO " . MyPDO::DB_FLAG . "joueur (nom,email,IPs,mdp,credits,creato,lastco) VALUES(?,?,?,?,?,now(),now()) ";
        $ret = MyPDO::getInstance()->query($query,$infos['nom'],$infos['email'],giveIP(), $infos['mdp'],self::$credits_depart);
        if($ret === MyPDO::QUERY_FAILED){
            return null;
        }
        // pour chaque joueur crée on crée un systeme qui sera celui de départ ( id_joueur = id_systeme départ)
        $id_joueur =  MyPDO::getInstance()->lastInsertId();
        $joueur = new Joueur($id_joueur);
        $id_systeme = Systeme::create();
        $systeme = new Systeme($id_systeme);
        $id_perso = Perso::create($joueur);
        $perso = new Perso($id_perso);
        Planete::fillSysteme($perso,$systeme);
        
        return $joueur;
    }
    
   
    
    public static function connecter(array $infos): ?object{
        $query = "select * from " . MyPDO::DB_FLAG . "joueur where email=? ";
        $ret = MyPDO::getInstance()->query($query,$infos['email']);
        if(empty($ret) || $ret === MyPDO::QUERY_FAILED || !password_verify($infos['mdp'], $ret[0]->mdp)){
            return null;
        }
        return $ret[0];
    }
        
    public static function liste(): array{
        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "joueur ";
        $ret = MyPDO::getInstance()->query($query);
        return $ret;
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

    public function load(int $id): void {
        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "joueur
                WHERE id=? ";
        $ret = $this->bdd->query($query, $id);
        if(empty($ret)){
            throw new UnexpectedValueException("Joueur n'existe pas");
        }
        
        $this->id = $ret[0]->id;
        $this->nom = $ret[0]->nom;
        $this->email = $ret[0]->email;
        $this->IPs = $ret[0]->IPs;

        $this->credits = $ret[0]->credits;
        $this->mdp = $ret[0]->mdp;
        $this->etape_tuto= $ret[0]->etape_tuto;
        $this->creato = $ret[0]->creato;
        $this->lastco = $ret[0]->lastco;

    }
    
        public function save(): void {     
        $query ="UPDATE " . MyPDO::DB_FLAG . "joueur 
            set
            nom = ?,
            email =?,
            IPs = ?,
            credits = ?,
            mdp =?,
            etape_tuto = ?,
            lastco = ?
            WHERE id =?";
        $ret = $this->bdd->query($query,          
                $this->nom,
                $this->email,
                $this->IPs,
                $this->credits,
                $this->mdp,
                $this->etape_tuto,
                $this->lastco,
                $this->id
                );
    }
    

    public function giveMainPerso(): Perso {
        $query = "SELECT id FROM " . MyPDO::DB_FLAG . "perso WHERE nom=? AND id_joueur=?";
        $ret = $this->bdd->query($query,          
                $this->nom,
                $this->id
        );
        return new Perso($ret[0]->id);
    
    }
    public function getNom(): string {
        return '<a target="_blank" href="?p=afficheJoueur&id_joueur='.$this->id.'" class="nomJoueur">'.htmlspecialchars($this->nom).'</a>';
    }  
       
}
