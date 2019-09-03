<?php
header('Content-Type: text/html;charset=UTF-8');
session_start();
spl_autoload_register(function ($class) {
    include_once 'inc/class/' . $class . '.class.php';
});


