<?php

/**
 * Charge automatiquement une class
 * @author Sgourmand
 */
spl_autoload_register(function (string $class) {
    include_once 'inc/class/' . $class . '.class.php';
});

function giveIP(): string{
    return $_SERVER['REMOTE_ADDR'];
}
