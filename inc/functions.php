<?php

/**
 * Charge automatiquement une class
 * @author Sgourmand
 */
spl_autoload_register(function (string $class) {
    include_once 'inc/class/' . $class . '.class.php';
});

function giveInputString(array $source, string $key): string{
    if(is_string($source[$key] ?? null)){
        return $source[$key];
    }
    return '';
}

function csrfField(): string{
    return '<input type="hidden" name="'.CSRF_KEY.'" value="'.$_SESSION[CSRF_KEY].'" />';
}

function __(string $cle, string $clePluriel, int $nombre): string{
    static $mots = null;
    if($mots === null){
        $mots = require 'inc/lang.fr.php';
    }
    $cleChoisie = $nombre > 1 ? $clePluriel : $cle;
    if(!isset($mots[$cleChoisie])){
        throw new UnexpectedValueException("Mot inconnu : ".$cleChoisie);
    }
    return $mots[$cleChoisie];
}

function giveIP(): string{
    return $_SERVER['REMOTE_ADDR'];
}
