<?php

class Vue {

    const PERCENT = 100;
    const EMBLEME_TAILLE = 32;
    const EMBLEME_VUE = 48;
    const EMBLEME_TRAIT = 3;
    const DOSSIER_IMAGES = 'img';
    const EXTENSION_OBJET = 'png';
    const EXTENSION_FOND = 'jpg';
    const EMBLEMES = array(
        Camp::EMPIRE => '<polygon points="24,3 43,14 43,34 24,45 5,34 5,14" fill="none" stroke="currentColor" stroke-width="3" stroke-linejoin="round"/><polygon points="24,13 34,29 14,29" fill="currentColor"/>',
        Camp::PIRATES => '<circle cx="24" cy="24" r="19" fill="none" stroke="currentColor" stroke-width="3"/><path d="M14 14L34 34M34 14L14 34" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>',
        Camp::REBELLION => '<polygon points="24,4 44,24 24,44 4,24" fill="none" stroke="currentColor" stroke-width="3" stroke-linejoin="round"/><circle cx="24" cy="24" r="7" fill="currentColor"/>',
    );

    public static function ressource(string $chemin): string {
        return $chemin.'?v='.filemtime($chemin);
    }

    public static function visuel(string $dossier, string $cle, ?string $iconeSecours = null): string {
        $chemin = self::DOSSIER_IMAGES.'/'.$dossier.'/'.$cle.'.'.self::EXTENSION_OBJET;
        if(is_file($chemin)){
            return '<span class="visuel visuel--image" style="background-image:url('.$chemin.')" aria-hidden="true"></span>';
        }
        if($iconeSecours === null){
            return '';
        }
        return Icone::html($iconeSecours);
    }

    public static function banniereCamp(?int $idCamp): string {
        if($idCamp === null || !isset(Camp::VISUELS[$idCamp])){
            return '';
        }
        $chemin = self::DOSSIER_IMAGES.'/camp/'.Camp::VISUELS[$idCamp].'.'.self::EXTENSION_FOND;
        if(!is_file($chemin)){
            return '';
        }
        return '<div class="banniere" style="background-image:url('.$chemin.')" aria-hidden="true"></div>';
    }

    public static function bandeau(string $nom): string {
        $chemin = self::DOSSIER_IMAGES.'/'.$nom.'.'.self::EXTENSION_FOND;
        if(!is_file($chemin)){
            return '';
        }
        return '<div class="bandeau" style="background-image:url('.$chemin.')" aria-hidden="true"></div>';
    }

    public static function fond(string $nom): string {
        $chemin = self::DOSSIER_IMAGES.'/'.$nom.'.'.self::EXTENSION_FOND;
        if(!is_file($chemin)){
            return '';
        }
        return '<div class="fond" style="background-image:url('.$chemin.')" aria-hidden="true"></div>';
    }

    public static function boutonReset(): string {
        return '<form class="reset" method="POST" action="?p='.RESET_PAGE.'" data-navigation onsubmit="return confirm('.htmlspecialchars(json_encode(RESET_CONFIRMATION, JSON_UNESCAPED_UNICODE), ENT_QUOTES).')">'
            .csrfField()
            .'<button type="submit" class="md3-nav-link md3-nav-link--bouton">'.RESET_LABEL.'</button></form>';
    }

    public static function filAriane(array $etapes): string {
        $html = '<nav class="fil" aria-label="Fil d\'Ariane"><ol>';
        foreach($etapes as list($libelle, $adresse)){
            if($adresse === null){
                $html .= '<li aria-current="page">'.htmlspecialchars($libelle).'</li>';
                continue;
            }
            $html .= '<li><a href="'.htmlspecialchars($adresse).'">'.htmlspecialchars($libelle).'</a></li>';
        }
        return $html.'</ol></nav>';
    }

    public static function pastille(string $texte, string $variante = ''): string {
        return '<span class="pastille'.($variante === '' ? '' : ' pastille--'.$variante).'">'.htmlspecialchars($texte).'</span>';
    }

    public static function composition(array $nombres): string {
        $html = '<div class="composition">';
        foreach($nombres as $type => $nombre){
            $html .= '<span class="composition__element">'.self::visuel('vaisseau', $type, Vaisseau::CATALOGUE[$type]['icone']).$nombre.' × '.htmlspecialchars(Vaisseau::CATALOGUE[$type]['nom']).'</span>';
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
        $logo = self::DOSSIER_IMAGES.'/logo/'.Camp::VISUELS[$idCamp].'.'.self::EXTENSION_OBJET;
        if(is_file($logo)){
            return '<img class="embleme embleme--image" src="'.$logo.'" width="'.$taille.'" height="'.$taille.'" alt="">';
        }
        return '<svg class="embleme" width="'.$taille.'" height="'.$taille.'" viewBox="0 0 '.self::EMBLEME_VUE.' '.self::EMBLEME_VUE.'" aria-hidden="true">'.self::EMBLEMES[$idCamp].'</svg>';
    }

}
