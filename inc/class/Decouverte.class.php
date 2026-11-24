<?php

class Decouverte {

    public static function ajouter(int $joueurId, int $systemeId): bool {
        $query = "INSERT INTO " . MyPDO::DB_FLAG . "decouverte (id_joueur,id_systeme,creato) VALUES(?,?,now())";
        return MyPDO::getInstance()->query($query,$joueurId,$systemeId) !== MyPDO::QUERY_FAILED;
    }

    public static function existe(int $joueurId, int $systemeId): bool {
        $query = "SELECT id_systeme FROM " . MyPDO::DB_FLAG . "decouverte WHERE id_joueur=? AND id_systeme=?";
        return !empty(MyPDO::getInstance()->query($query,$joueurId,$systemeId));
    }

}
