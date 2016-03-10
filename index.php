<?php
require_once 'inc/header.php';

$planet = new Planet();
Tools::print_pre($planet->listPlanet(2));

?>