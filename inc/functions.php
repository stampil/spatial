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

function giveIP(): string{
    return $_SERVER['REMOTE_ADDR'];
}
