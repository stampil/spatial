<?php

class Flotte {

    const NOM_LONGUEUR_MAX = 32;
    const NOM_PAR_DEFAUT = 'Flotte ';
    const VAISSEAUX_MINIMUM = 2;

    private MyPDO $bdd;
    public int $id;
    public int $id_joueur;
    public string $nom;

    public static function liste(int $joueurId): array {
        $query = "SELECT * FROM " . MyPDO::DB_FLAG . "flotte WHERE id_joueur=? ORDER BY id";
        return MyPDO::getInstance()->query($query,$joueurId);
    }

    public static function creer(Joueur $joueur, Planete $planete, array $vaisseauxIds): ?string {
        $ids = array_values(array_unique(array_map('intval', $vaisseauxIds)));
        if(count($ids) < self::VAISSEAUX_MINIMUM){
            return 'Une flotte demande au moins '.self::VAISSEAUX_MINIMUM.' vaisseaux.';
        }
        $db = MyPDO::getInstance();
        $marquesIds = implode(',', array_fill(0, count($ids), '?'));
        $types = Vaisseau::typesDeFlotte();
        $marquesTypes = implode(',', array_fill(0, count($types), '?'));
        $conditions = "WHERE id IN ($marquesIds) AND id_joueur=? AND id_planete=? AND arrivee IS NULL AND id_flotte IS NULL AND type IN ($marquesTypes)";
        $parametres = array_merge($ids, array($joueur->id, $planete->id), $types);
        $eligibles = (int) $db->query("SELECT COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau " . $conditions,...$parametres)[0]->nombre;
        if($eligibles !== count($ids)){
            return 'La situation a changé sur cette planète : recommencez.';
        }
        $query = "SELECT COALESCE(MAX(numero), 0) + 1 AS suivant FROM " . MyPDO::DB_FLAG . "flotte WHERE id_joueur=?";
        $numero = (int) $db->query($query,$joueur->id)[0]->suivant;
        $db->query("INSERT INTO " . MyPDO::DB_FLAG . "flotte (id_joueur,nom,numero,creato) VALUES (?,?,?,?)",$joueur->id,self::NOM_PAR_DEFAUT.$numero,$numero,Tools::now());
        $flotteId = (int) $db->lastInsertId();
        $db->query("UPDATE " . MyPDO::DB_FLAG . "vaisseau SET id_flotte=? " . $conditions,$flotteId,...$parametres);
        if($db->lastRowCount !== count($ids)){
            $db->query("UPDATE " . MyPDO::DB_FLAG . "vaisseau SET id_flotte=NULL WHERE id_flotte=?",$flotteId);
            $db->query("DELETE FROM " . MyPDO::DB_FLAG . "flotte WHERE id=?",$flotteId);
            return 'La situation a changé sur cette planète : recommencez.';
        }
        return null;
    }

    public static function renommer(Joueur $joueur, int $flotteId, string $nom): ?string {
        $nom = trim($nom);
        if($nom === '' || mb_strlen($nom) > self::NOM_LONGUEUR_MAX){
            return 'Le nom de la flotte doit faire entre 1 et '.self::NOM_LONGUEUR_MAX.' caractères.';
        }
        MyPDO::getInstance()->query("UPDATE " . MyPDO::DB_FLAG . "flotte SET nom=? WHERE id=? AND id_joueur=?",$nom,$flotteId,$joueur->id);
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
            return $this->nom.' est déjà en déplacement.';
        }
        if($depart === $destination->id){
            return $this->nom.' est déjà sur cette planète.';
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
            return $this->nom.' est déjà en déplacement.';
        }
        $query = "UPDATE " . MyPDO::DB_FLAG . "vaisseau e JOIN " . MyPDO::DB_FLAG . "vaisseau h ON h.id=e.id_hote SET e.id_destination=?, e.depart=?, e.arrivee=? WHERE h.id_flotte=? AND e.arrivee IS NULL";
        $this->bdd->query($query,$destination->id,$heureDepart,$arrivee,$this->id);
        Tools::addMessage($this->nom.' : départ vers '.$destination->nom.', arrivée dans '.gmdate('i:s', $duree).'.');
        return null;
    }

    public function dissoudre(): void {
        $this->bdd->query("UPDATE " . MyPDO::DB_FLAG . "vaisseau SET id_flotte=NULL WHERE id_flotte=?",$this->id);
        $this->bdd->query("DELETE FROM " . MyPDO::DB_FLAG . "flotte WHERE id=?",$this->id);
    }

}
