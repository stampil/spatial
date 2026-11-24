<?php

class Classement {

    public static function joueurs(): array {
        $planetes = array();
        $query = "SELECT gouverneur, COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "planete WHERE gouverneur<>0 GROUP BY gouverneur";
        foreach(MyPDO::getInstance()->query($query) as $ligne){
            $planetes[$ligne->gouverneur] = (int) $ligne->nombre;
        }
        $forces = array();
        $query = "SELECT id_joueur, type, COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "vaisseau GROUP BY id_joueur, type";
        foreach(MyPDO::getInstance()->query($query) as $ligne){
            $forces[$ligne->id_joueur] = ($forces[$ligne->id_joueur] ?? 0) + $ligne->nombre * Vaisseau::CATALOGUE[$ligne->type]['force'];
        }
        $classement = array();
        foreach(Joueur::liste() as $ligne){
            array_push($classement, array('id' => (int) $ligne->id, 'planetes' => $planetes[$ligne->id] ?? 0, 'force' => $forces[$ligne->id] ?? 0));
        }
        usort($classement, function (array $premier, array $second): int {
            return array($second['planetes'], $second['force'], $premier['id']) <=> array($premier['planetes'], $premier['force'], $second['id']);
        });
        return $classement;
    }

    public static function camps(): array {
        $planetes = array();
        $query = "SELECT id_camp, COUNT(*) AS nombre FROM " . MyPDO::DB_FLAG . "planete WHERE id_camp IS NOT NULL GROUP BY id_camp";
        foreach(MyPDO::getInstance()->query($query) as $ligne){
            $planetes[$ligne->id_camp] = (int) $ligne->nombre;
        }
        $classement = array();
        foreach(Camp::liste() as $ligne){
            array_push($classement, array('id' => (int) $ligne->id, 'planetes' => $planetes[$ligne->id] ?? 0));
        }
        usort($classement, function (array $premier, array $second): int {
            return array($second['planetes'], $premier['id']) <=> array($premier['planetes'], $second['id']);
        });
        return $classement;
    }

}
