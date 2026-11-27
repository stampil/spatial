<?php

class Analyse {

    public static function enregistrer(Joueur $joueur, Planete $planete): void {
        $donnees = array(
            'troupes' => $planete->troupes,
            'bouclier' => $planete->bouclier,
            'canon' => $planete->canon,
            'flotte' => Combat::defenseurs($planete),
            'batiments' => $planete->batimentsTotal(),
            'boucliers' => $planete->nb_bouclier_planetaire,
            'canons' => $planete->nb_canon_orbital,
            'gouverneur' => $planete->pnj_endurance,
        );
        $query = "REPLACE INTO " . MyPDO::DB_FLAG . "analyse_planete (id_joueur,id_planete,donnees,analyse_le) VALUES (?,?,?,?)";
        MyPDO::getInstance()->query($query,$joueur->id,$planete->id,json_encode($donnees),Tools::now());
    }

    public static function derniere(int $joueurId, int $planeteId): ?array {
        $query = "SELECT donnees, analyse_le FROM " . MyPDO::DB_FLAG . "analyse_planete WHERE id_joueur=? AND id_planete=?";
        $ligne = MyPDO::getInstance()->query($query,$joueurId,$planeteId)[0] ?? null;
        if($ligne === null){
            return null;
        }
        $analyse = json_decode($ligne->donnees, true);
        $analyse['le'] = $ligne->analyse_le;
        return $analyse;
    }

    public static function planetesAnalysees(int $joueurId): array {
        $analysees = array();
        foreach(MyPDO::getInstance()->query("SELECT id_planete FROM " . MyPDO::DB_FLAG . "analyse_planete WHERE id_joueur=?",$joueurId) as $ligne){
            $analysees[$ligne->id_planete] = true;
        }
        return $analysees;
    }

    public static function releve(Joueur $joueur, Planete $planete): ?array {
        if($planete->gouverneur === $joueur->id){
            return array('troupes' => $planete->troupes, 'bouclier' => $planete->bouclier, 'canon' => $planete->canon);
        }
        return self::derniere($joueur->id, $planete->id);
    }

}
