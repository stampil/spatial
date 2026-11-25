<?php

class Camp {

    const PIRATES = 1;
    const EMPIRE = 2;
    const REBELLION = 3;
    const DISCRETS = array(self::PIRATES, self::REBELLION);
    const EMPIRE_REMISE_POURCENT = 20;
    const PERCENT = 100;
    const AVANTAGES = array(
        self::PIRATES => 'Détourne les ressources des planètes sans les conquérir.',
        self::EMPIRE => 'Unités à -%d %%, toujours visible sur la carte.',
        self::REBELLION => 'Invisible sur la carte tant qu\'on ne l\'a pas découverte.',
    );
    const VISUELS = array(self::PIRATES => 'pirates', self::EMPIRE => 'empire', self::REBELLION => 'rebellion');
    const SANS_CAMP_NOM = 'Sans camp';
    const SANS_CAMP_COULEUR = '#79747E';

    private MyPDO $bdd;
    public int $id;
    public string $nom;
    public string $histoire;
    public bool $jouable;
    public string $couleur;

    public static function liste(): array {
        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "camp ";
        $ret = MyPDO::getInstance()->query($query);
        return $ret;
    }

    public static function listeJouables(): array {
        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "camp
                WHERE jouable=1 ";
        $ret = MyPDO::getInstance()->query($query);
        return $ret;
    }

    public static function estJouable(int $id): bool {
        foreach(self::listeJouables() as $ligne){
            if((int) $ligne->id === $id){
                return true;
            }
        }
        return false;
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

    private function load(int $id): void {
        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "camp
                WHERE id=? ";
        $ret = $this->bdd->query($query, $id);
        if(empty($ret)){
            throw new UnexpectedValueException("Ce camp n'existe pas");
        }

        $this->id = $ret[0]->id;
        $this->nom = $ret[0]->nom;
        $this->histoire = $ret[0]->histoire;
        $this->jouable = (bool) $ret[0]->jouable;
        $this->couleur = $ret[0]->couleur;
    }

    public static function libelle(?int $id_camp): string {
        $nom = self::SANS_CAMP_NOM;
        $couleur = self::SANS_CAMP_COULEUR;
        if($id_camp !== null){
            $camp = new Camp($id_camp);
            $nom = $camp->nom;
            $couleur = $camp->couleur;
        }
        return '<span class="md3-camp-dot" style="background-color:'.htmlspecialchars($couleur).'"></span> '.htmlspecialchars($nom);
    }

    public function avantage(): string {
        return sprintf(self::AVANTAGES[$this->id], self::EMPIRE_REMISE_POURCENT);
    }

    public function adversaires(): array {
        $query = "SELECT *
                FROM " . MyPDO::DB_FLAG . "camp
                WHERE id<>? ";
        $ret = $this->bdd->query($query, $this->id);
        return $ret;
    }

}
