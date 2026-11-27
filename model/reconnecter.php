<?php
$info = Joueur::connecterParJeton(giveInputString($_POST, 'jeton'));
ob_clean();
if($info === null){
    http_response_code(401);
    exit();
}
(new Joueur($info->id))->ouvrirSession();
exit();
?>
