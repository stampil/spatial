<?php

class Univers {

    const PLAN = array(
        array('nombre' => 6, 'imperial' => true),
        array('nombre' => 3, 'camps' => array(Camp::PIRATES)),
        array('nombre' => 3, 'camps' => array(Camp::EMPIRE, Camp::PIRATES, null)),
        array('nombre' => 4, 'camps' => array(Camp::REBELLION)),
    );

    const POSITIONS = array(
        array(500, 300), array(380, 420), array(620, 420), array(440, 560), array(560, 560), array(500, 430),
        array(120, 180), array(880, 160), array(860, 800),
        array(260, 330), array(740, 330), array(700, 640),
        array(140, 480), array(480, 780), array(270, 740), array(880, 520),
    );

    const CAMPS_DECOUVERTE = array(Camp::PIRATES, null);

    const TUTO_BOUCLIER = 5;
    const TUTO_CANON = 5;
    const TUTO_TROUPES = 1;

    public static function estPeuple(): bool {
        $query = "SELECT COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "planete WHERE id_camp IS NOT NULL AND gouverneur=0";
        $ret = MyPDO::getInstance()->query($query);
        return !empty($ret) && $ret[0]->nombre > 0;
    }

    public static function creerVoisin(Joueur $joueur, ?Systeme $depart = null): void {
        $ancre = $depart === null ? null : array($depart->x, $depart->y);
        $Systeme = new Systeme(Systeme::create(false, Systeme::positionLibre($ancre)));
        Planete::fillSystemeIA($Systeme, array(Camp::PIRATES, null), Camp::PIRATES);
        $query = "UPDATE " . MyPDO::DB_FLAG . "planete SET bouclier=?, canon=?, troupes=? WHERE id_systeme=? ORDER BY id LIMIT 1";
        MyPDO::getInstance()->query($query,self::TUTO_BOUCLIER,self::TUTO_CANON,self::TUTO_TROUPES,$Systeme->id);
        $query = "UPDATE " . MyPDO::DB_FLAG . "joueur SET id_systeme_voisin=? WHERE id=?";
        MyPDO::getInstance()->query($query,$Systeme->id,$joueur->id);
        $joueur->id_systeme_voisin = $Systeme->id;
    }

    public static function peupler(): array {
        $noms = array();
        $numero = 0;
        foreach(self::PLAN as $etape){
            for($i=0; $i<$etape['nombre']; $i++){
                $Systeme = new Systeme(Systeme::create(true, self::POSITIONS[$numero]));
                if(isset($etape['imperial'])){
                    Planete::fillSystemeImperial($Systeme, true);
                }
                else{
                    Planete::fillSystemeIA($Systeme, $etape['camps'], null, true);
                }
                array_push($noms, $Systeme->nom);
                $numero++;
            }
        }
        return $noms;
    }

}
