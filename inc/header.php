<?php
header('Content-Type: text/html;charset=UTF-8');
date_default_timezone_set("Europe/Paris");
session_start();
require 'functions.php';
spl_autoload_register(function ($class) {
    include_once 'inc/class/' . $class . '.class.php';
});


