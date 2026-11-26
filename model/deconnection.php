<?php
if(isset($Joueur)){
    $Joueur->oublierJetons();
}
session_destroy();
require 'inc/oublier.php';
exit();
?>
