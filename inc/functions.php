<?php

/**
 * Charge automatiquement une class
 * @author Sgourmand
 */
spl_autoload_register(function ($class) {
    include_once 'inc/class/' . $class . '.class.php';
});

function giveIP(){
    
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
        $ipAddress = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
    }
    return $ipAddress;
}