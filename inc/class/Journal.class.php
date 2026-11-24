<?php

class Journal {

    const LONGUEUR_MAX = 500;
    const RECENTS = 15;

    public static function ajouter(int $joueurId, string $message): void {
        $query = "INSERT INTO " . MyPDO::DB_FLAG . "journal (id_joueur,message,creato) VALUES (?,?,?)";
        MyPDO::getInstance()->query($query,$joueurId,mb_substr($message, 0, self::LONGUEUR_MAX),Tools::now());
    }

    public static function recents(int $joueurId): array {
        $query = "SELECT message, creato FROM " . MyPDO::DB_FLAG . "journal WHERE id_joueur=? ORDER BY id DESC LIMIT " . self::RECENTS;
        return MyPDO::getInstance()->query($query,$joueurId);
    }

}
