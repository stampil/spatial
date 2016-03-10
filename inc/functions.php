<?php

/**
 * Charge automatiquement une class
 * @author Sgourmand
 */
spl_autoload_register(function ($class) {
    include_once 'inc/class/' . $class . '.class.php';
});