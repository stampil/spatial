<?php
header('Content-Type: text/html;charset=UTF-8');
date_default_timezone_set("Europe/Paris");
session_start(array('cookie_httponly' => true, 'cookie_samesite' => 'Lax', 'use_strict_mode' => true));
ob_start();
require 'const.php';
require 'functions.php';

if(empty($_SESSION[CSRF_KEY])){
    $_SESSION[CSRF_KEY] = bin2hex(random_bytes(CSRF_TOKEN_BYTES));
}
