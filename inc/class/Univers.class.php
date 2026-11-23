<?php

class Univers {

    const PLAN = array(
        array('nombre' => 6, 'camps' => array(Camp::EMPIRE)),
        array('nombre' => 3, 'camps' => array(Camp::PIRATES)),
        array('nombre' => 3, 'camps' => array(Camp::EMPIRE, Camp::PIRATES, null)),
        array('nombre' => 4, 'camps' => array(null)),
    );

    public static function estPeuple(): bool {
        $query = "SELECT COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "planete WHERE id_camp IS NOT NULL AND gouverneur=0";
        $ret = MyPDO::getInstance()->query($query);
        return !empty($ret) && $ret[0]->nombre > 0;
    }

    public static function peupler(): array {
        $noms = array();
        foreach(self::PLAN as $etape){
            for($i=0; $i<$etape['nombre']; $i++){
                $Systeme = new Systeme(Systeme::create());
                Planete::fillSystemeIA($Systeme, $etape['camps']);
                array_push($noms, $Systeme->nom);
            }
        }
        return $noms;
    }

}
