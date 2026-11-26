<?php

class Flotte {

    const NOM_LONGUEUR_MAX = 32;

    private MyPDO $bdd;
    public int $id;
    public int $id_joueur;
    public string $nom;

    public static function liste(int $joueurId): array {
        $query = "SELECT * FROM " . MyPDO::DB_FLAG . "flotte WHERE id_joueur=? ORDER BY id";
        return MyPDO::getInstance()->query($query,$joueurId);
    }

    public static function creer(Joueur $joueur, Planete $planete, string $nom, array $nombres): ?string {
        $nom = trim($nom);
        if($nom === '' || mb_strlen($nom) > self::NOM_LONGUEUR_MAX){
            return 'Le nom de la flotte doit faire entre 1 et '.self::NOM_LONGUEUR_MAX.' caractères.';
        }
        $stock = Vaisseau::stock($planete->id, $joueur->id);
        $total = 0;
        foreach(Vaisseau::typesDeFlotte() as $type){
            $nombre = $nombres[$type] ?? 0;
            if($nombre < 0 || $nombre > ($stock[$type] ?? 0)){
                return 'Pas assez de vaisseaux : '.Vaisseau::CATALOGUE[$type]['nom'].' (disponibles : '.($stock[$type] ?? 0).').';
            }
            $total += $nombre;
        }
        if($total === 0){
            return 'Une flotte demande au moins un vaisseau.';
        }
        $db = MyPDO::getInstance();
        $db->query("INSERT INTO " . MyPDO::DB_FLAG . "flotte (id_joueur,nom,creato) VALUES (?,?,?)",$joueur->id,$nom,Tools::now());
        $flotteId = (int) $db->lastInsertId();
        foreach(Vaisseau::typesDeFlotte() as $type){
            $nombre = $nombres[$type] ?? 0;
            if($nombre === 0){
                continue;
            }
            $query = "UPDATE " . MyPDO::DB_FLAG . "vaisseau SET id_flotte=? WHERE id_joueur=? AND id_planete=? AND type=? AND arrivee IS NULL AND id_flotte IS NULL ORDER BY id LIMIT " . $nombre;
            $db->query($query,$flotteId,$joueur->id,$planete->id,$type);
            if($db->lastRowCount !== $nombre){
                $db->query("UPDATE " . MyPDO::DB_FLAG . "vaisseau SET id_flotte=NULL WHERE id_flotte=?",$flotteId);
                $db->query("DELETE FROM " . MyPDO::DB_FLAG . "flotte WHERE id=?",$flotteId);
                return 'La situation a changé sur cette planète : recommencez.';
            }
        }
        return null;
    }

    public static function dissoudreVides(int $joueurId): void {
        $query = "DELETE FROM " . MyPDO::DB_FLAG . "flotte WHERE id_joueur=? AND id NOT IN (SELECT id_flotte FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_flotte IS NOT NULL)";
        MyPDO::getInstance()->query($query,$joueurId);
    }

    public function __construct(int $id) {
        $this->bdd = MyPDO::getInstance();
        $this->load($id);
    }

    private function load(int $id): void {
        $ret = $this->bdd->query("SELECT * FROM " . MyPDO::DB_FLAG . "flotte WHERE id=?",$id);
        if(empty($ret)){
            throw new UnexpectedValueException("Cette flotte n'existe pas");
        }
        $this->id = $ret[0]->id;
        $this->id_joueur = $ret[0]->id_joueur;
        $this->nom = $ret[0]->nom;
    }

    public function composition(): array {
        $query = "SELECT type, COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_flotte=? GROUP BY type";
        $composition = array();
        foreach($this->bdd->query($query,$this->id) as $ligne){
            $composition[$ligne->type] = (int) $ligne->nombre;
        }
        return $composition;
    }

    public function vaisseaux(): array {
        return $this->bdd->query("SELECT id, type, nom FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_flotte=? ORDER BY id",$this->id);
    }

    public function planeteAuRepos(): ?int {
        $query = "SELECT id_planete, COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_flotte=? AND arrivee IS NULL GROUP BY id_planete";
        $lignes = $this->bdd->query($query,$this->id);
        if(count($lignes) !== 1 || (int) $lignes[0]->nombre !== array_sum($this->composition())){
            return null;
        }
        return (int) $lignes[0]->id_planete;
    }

    public function enRoute(): ?object {
        $query = "SELECT id_planete, id_destination, depart, arrivee FROM " . MyPDO::DB_FLAG . "vaisseau WHERE id_flotte=? AND arrivee IS NOT NULL ORDER BY arrivee DESC LIMIT 1";
        $ret = $this->bdd->query($query,$this->id);
        return $ret[0] ?? null;
    }

    public function deplacer(Joueur $joueur, Planete $destination): ?string {
        $depart = $this->planeteAuRepos();
        if($depart === null){
            return 'La flotte '.$this->nom.' est déjà en déplacement.';
        }
        if($depart === $destination->id){
            return 'La flotte '.$this->nom.' est déjà sur cette planète.';
        }
        if(!$joueur->peutCibler($destination->systeme)){
            return 'Destination inconnue.';
        }
        $duree = 0;
        foreach(array_keys($this->composition()) as $type){
            $duree = max($duree, Vaisseau::dureeTrajet($joueur, $type, new Planete($depart), $destination));
        }
        $heureDepart = Tools::now();
        $arrivee = Tools::nowPlus($duree);
        $query = "UPDATE " . MyPDO::DB_FLAG . "vaisseau SET id_destination=?, depart=?, arrivee=? WHERE id_flotte=? AND arrivee IS NULL";
        $this->bdd->query($query,$destination->id,$heureDepart,$arrivee,$this->id);
        if($this->bdd->lastRowCount === 0){
            return 'La flotte '.$this->nom.' est déjà en déplacement.';
        }
        $query = "UPDATE " . MyPDO::DB_FLAG . "vaisseau e JOIN " . MyPDO::DB_FLAG . "vaisseau h ON h.id=e.id_hote SET e.id_destination=?, e.depart=?, e.arrivee=? WHERE h.id_flotte=? AND e.arrivee IS NULL";
        $this->bdd->query($query,$destination->id,$heureDepart,$arrivee,$this->id);
        Tools::addMessage('Flotte '.$this->nom.' : départ vers '.$destination->nom.', arrivée dans '.gmdate('i:s', $duree).'.');
        return null;
    }

    public function dissoudre(): void {
        $this->bdd->query("UPDATE " . MyPDO::DB_FLAG . "vaisseau SET id_flotte=NULL WHERE id_flotte=?",$this->id);
        $this->bdd->query("DELETE FROM " . MyPDO::DB_FLAG . "flotte WHERE id=?",$this->id);
    }

}
