<?php

class Icone {

    const VUE = 24;
    const TRAIT = 2;
    const DOSSIER_IMAGES = 'icone';
    const DESSINS = array(
        'credits' => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="4"/>',
        'revenu' => '<polyline points="3 17 9 11 13 15 21 7"/><polyline points="15 7 21 7 21 13"/>',
        'planetes' => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3c3 3 3 15 0 18c-3-3-3-15 0-18"/>',
        'fusee' => '<path d="M12 3c3 2 5 6 5 10l-2 3H9l-2-3c0-4 2-8 5-10z"/><circle cx="12" cy="10" r="1.5"/><path d="M9 19l-1.5 3M15 19l1.5 3M12 19v3"/>',
        'menu' => '<path d="M4 6h16M4 12h16M4 18h16"/>',
        'son' => '<path d="M4 9v6h4l5 4V5L8 9z"/><path d="M16 9c1.5 1.5 1.5 4.5 0 6M19 6c3 3 3 9 0 12"/>',
        'muet' => '<path d="M4 9v6h4l5 4V5L8 9z"/><path d="M17 9l5 6M22 9l-5 6"/>',
        'carte' => '<path d="M3 6l6-2 6 2 6-2v14l-6 2-6-2-6 2z"/><path d="M9 4v14M15 6v14"/>',
        'persos' => '<circle cx="9" cy="8" r="3"/><path d="M3 20c0-3 2.5-5 6-5s6 2 6 5"/><circle cx="17" cy="9" r="2.5"/><path d="M17 14c2.5 0 4 1.5 4 4"/>',
        'classement' => '<rect x="4" y="12" width="4" height="8"/><rect x="10" y="5" width="4" height="15"/><rect x="16" y="9" width="4" height="11"/>',
        'mission' => '<path d="M5 21V4"/><path d="M5 4h12l-2 4 2 4H5"/>',
        'usine_legere' => '<path d="M3 20V10l6 3V10l6 3V6h6v14z"/>',
        'usine_moyenne' => '<circle cx="12" cy="12" r="3"/><path d="M12 3v3M12 18v3M3 12h3M18 12h3M5.6 5.6l2.1 2.1M16.3 16.3l2.1 2.1M18.4 5.6l-2.1 2.1M7.7 16.3l-2.1 2.1"/>',
        'usine_lourde' => '<path d="M3 21V9l9-6 9 6v12z"/><rect x="8" y="13" width="8" height="8"/>',
        'ferme' => '<path d="M12 21V9"/><path d="M12 13c-4 0-5-3-5-6 3 0 5 2 5 6z"/><path d="M12 11c4 0 5-3 5-6-3 0-5 2-5 6z"/>',
        'academie' => '<path d="M2 9l10-5 10 5-10 5z"/><path d="M6 11v5c0 1.5 3 3 6 3s6-1.5 6-3v-5"/><path d="M22 9v6"/>',
        'bouclier' => '<path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/>',
        'canon' => '<circle cx="12" cy="12" r="7"/><path d="M12 2v5M12 17v5M2 12h5M17 12h5"/>',
        'revolte' => '<path d="M12 3c1 4 5 5 5 10a5 5 0 0 1-10 0c0-2 1-3 2-4 0 2 1 3 2 3 0-3-1-6 1-9z"/>',
        'force' => '<path d="M6 8v8M18 8v8M3 10v4M21 10v4M6 12h12"/>',
        'endurance' => '<path d="M12 20s-8-5-8-11a4.5 4.5 0 0 1 8-2.5A4.5 4.5 0 0 1 20 9c0 6-8 11-8 11z"/>',
        'diplomatie' => '<path d="M12 4v16M6 20h12M4 8h16"/><path d="M4 8l-2 6a3 3 0 0 0 4 0z"/><path d="M20 8l-2 6a3 3 0 0 0 4 0z"/>',
        'heros' => '<path d="M12 3l2.6 5.4 5.9.9-4.3 4.1 1 5.8L12 16.5 6.8 19.2l1-5.8L3.5 9.3l5.9-.9z"/>',
        'assassin' => '<path d="M6 18L18 6"/><path d="M12 6l6 6"/><path d="M18 6l3-3"/>',
        'chasseur' => '<path d="M12 2l2 7 8 4v2l-8-2v5l3 2v1l-5-1-5 1v-1l3-2v-5l-8 2v-2l8-4z"/>',
        'reconnaissance' => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="4.5"/><path d="M12 12L18.4 5.6"/>',
        'transport' => '<path d="M3 16V8h12l5 5v3z"/><circle cx="7" cy="18" r="2"/><circle cx="17" cy="18" r="2"/>',
        'fregate' => '<path d="M3 12l5-4h9l4 4-4 4H8z"/><path d="M8 8v8"/>',
        'barge' => '<path d="M3 8l9-5 9 5v8l-9 5-9-5z"/><path d="M3 8l9 5 9-5"/><path d="M12 13v8"/>',
        'troupe' => '<path d="M6 10l6-5 6 5M6 16l6-5 6 5"/>',
        'emplacements' => '<rect x="3" y="3" width="8" height="8"/><rect x="13" y="3" width="8" height="8"/><rect x="3" y="13" width="8" height="8"/><rect x="13" y="13" width="8" height="8"/>',
        'ajouter' => '<path d="M12 5v14M5 12h14"/>',
        'construire' => '<path d="M15 4a4 4 0 0 0-4 5l-7 7 3 3 7-7a4 4 0 0 0 5-4l-3 2-2-2 2-3z"/>',
        'saboter' => '<path d="M4 20l8-8"/><path d="M14 4l6 6-3 1-2 4-3-3 4-2z"/><path d="M3 6l3 3M6 3l3 3"/>',
        'restrictor' => '<circle cx="12" cy="12" r="4"/><circle cx="12" cy="12" r="9"/><path d="M12 3v5M12 16v5M3 12h5M16 12h5"/>',
        'attaque' => '<path d="M5 19l9-9"/><path d="M14 4l6 6-4 1-3-3z"/><path d="M8 14l-3 5"/>',
        'attaque_flotte' => '<path d="M4 18l5-5 5 5M4 11l5-5 5 5"/><path d="M16 6l4 4-4 4"/>',
        'debarquement' => '<path d="M12 3v12M7 10l5 5 5-5"/><path d="M4 21h16"/>',
        'voyager' => '<path d="M5 19L19 5M9 5h10v10"/>',
        'chercher' => '<circle cx="11" cy="11" r="6"/><path d="M16 16l5 5"/>',
        'reconnaitre' => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="3"/><path d="M12 3v3M12 18v3"/>',
        'embarquer' => '<path d="M4 12h12M12 6l6 6-6 6"/><path d="M20 4v16"/>',
        'decharger' => '<path d="M20 12H8M12 6l-6 6 6 6"/><path d="M4 4v16"/>',
        'renommer' => '<path d="M4 20l4-1 11-11-3-3L5 16z"/><path d="M14 7l3 3"/>',
        'dissoudre' => '<path d="M6 6l12 12M18 6L6 18"/>',
        'combattre' => '<path d="M5 5l14 14M19 5L5 19"/><path d="M5 5h4M19 5h-4"/>',
        'laisser' => '<circle cx="12" cy="12" r="9"/><path d="M8 12h8"/>',
        'flotte' => '<path d="M7 3c1.5 1.5 3 4 3 8v7H4v-7c0-4 1.5-6.5 3-8z"/><path d="M17 3c1.5 1.5 3 4 3 8v7h-6v-7c0-4 1.5-6.5 3-8z"/>',
        'route' => '<path d="M3 12h14M13 6l6 6-6 6"/>',
        'info' => '<circle cx="12" cy="12" r="9"/><path d="M12 11v6M12 7.5v.5"/>',
        'actions' => '<path d="M13 2L5 14h6l-1 8 8-12h-6z"/>',
        'quartier' => '<path d="M3 3h8v10H3zM13 3h8v6h-8zM13 11h8v10h-8zM3 15h8v6H3z"/>',
        'journal' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'trophee' => '<path d="M8 4h8v5a4 4 0 0 1-8 0zM12 13v4M8 20h8M8 6H4v2a3 3 0 0 0 4 3M16 6h4v2a3 3 0 0 1-4 3"/>',
        'connexion' => '<path d="M10 4h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-8M4 12h10M10 8l4 4-4 4"/>',
        'inscription' => '<circle cx="10" cy="8" r="4"/><path d="M3 20c0-4 3-6 7-6M18 8v6M15 11h6"/>',
    );

    public static function html(string $nom): string {
        if(!isset(self::DESSINS[$nom])){
            throw new UnexpectedValueException("Icône inconnue : ".$nom);
        }
        $chemin = Vue::DOSSIER_IMAGES.'/'.self::DOSSIER_IMAGES.'/'.$nom.'.'.Vue::EXTENSION_OBJET;
        if(is_file($chemin)){
            return '<span class="icone icone--image" style="width:'.self::VUE.'px;height:'.self::VUE.'px;-webkit-mask-image:url('.$chemin.');mask-image:url('.$chemin.')" aria-hidden="true"></span>';
        }
        return '<svg class="icone" width="'.self::VUE.'" height="'.self::VUE.'" viewBox="0 0 '.self::VUE.' '.self::VUE.'" fill="none" stroke="currentColor" stroke-width="'.self::TRAIT.'" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.self::DESSINS[$nom].'</svg>';
    }

}
