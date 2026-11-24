<?php

class Vue {

    const PERCENT = 100;
    const EMBLEME_TAILLE = 32;
    const EMBLEME_VUE = 48;
    const EMBLEME_TRAIT = 3;
    const EMBLEMES = array(
        Camp::EMPIRE => '<polygon points="24,3 43,14 43,34 24,45 5,34 5,14" fill="none" stroke="currentColor" stroke-width="3" stroke-linejoin="round"/><polygon points="24,13 34,29 14,29" fill="currentColor"/>',
        Camp::PIRATES => '<circle cx="24" cy="24" r="19" fill="none" stroke="currentColor" stroke-width="3"/><path d="M14 14L34 34M34 14L14 34" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>',
        Camp::REBELLION => '<polygon points="24,4 44,24 24,44 4,24" fill="none" stroke="currentColor" stroke-width="3" stroke-linejoin="round"/><circle cx="24" cy="24" r="7" fill="currentColor"/>',
    );

    public static function pastille(string $texte, string $variante = ''): string {
        return '<span class="pastille'.($variante === '' ? '' : ' pastille--'.$variante).'">'.htmlspecialchars($texte).'</span>';
    }

    public static function composition(array $nombres): string {
        $html = '<div class="composition">';
        foreach($nombres as $type => $nombre){
            $html .= '<span class="composition__element">'.Icone::html(Vaisseau::CATALOGUE[$type]['icone']).$nombre.' × '.htmlspecialchars(Vaisseau::CATALOGUE[$type]['nom']).'</span>';
        }
        return $html.'</div>';
    }

    public static function jauge(string $nom, int $valeur, int $maximum, string $icone, string $variante = ''): string {
        $part = $maximum <= 0 ? 0 : min(self::PERCENT, intdiv($valeur * self::PERCENT, $maximum));
        return '<div class="jauge'.($variante === '' ? '' : ' jauge--'.$variante).'" role="meter" aria-label="'.htmlspecialchars($nom).'" aria-valuemin="0" aria-valuemax="'.$maximum.'" aria-valuenow="'.$valeur.'">'
            .'<span class="jauge__nom">'.Icone::html($icone).' '.htmlspecialchars($nom).'</span>'
            .'<span class="jauge__piste"><span class="jauge__remplissage" style="width:'.$part.'%"></span></span>'
            .'<span class="jauge__valeur">'.$valeur.' / '.$maximum.'</span>'
            .'</div>';
    }

    public static function progression(string $nom, int $restant, int $duree, string $icone): string {
        $restant = max(0, $restant);
        $part = $duree <= 0 ? self::PERCENT : max(0, min(self::PERCENT, intdiv(($duree - $restant) * self::PERCENT, $duree)));
        return '<div class="jauge jauge--progression" role="progressbar" aria-label="'.htmlspecialchars($nom).'" data-restant="'.$restant.'" data-duree="'.$duree.'">'
            .'<span class="jauge__nom">'.Icone::html($icone).' '.htmlspecialchars($nom).'</span>'
            .'<span class="jauge__piste"><span class="jauge__remplissage" style="width:'.$part.'%"></span></span>'
            .'<span class="jauge__valeur">'.gmdate($restant >= 3600 ? 'G:i:s' : 'i:s', $restant).'</span>'
            .'</div>';
    }

    public static function emblemeCamp(?int $idCamp, int $taille = self::EMBLEME_TAILLE): string {
        if($idCamp === null || !isset(self::EMBLEMES[$idCamp])){
            return '';
        }
        return '<svg class="embleme" width="'.$taille.'" height="'.$taille.'" viewBox="0 0 '.self::EMBLEME_VUE.' '.self::EMBLEME_VUE.'" aria-hidden="true">'.self::EMBLEMES[$idCamp].'</svg>';
    }

}
