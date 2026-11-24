<?php
if(!empty($_GET['id_joueur'])){
    $id_joueur = (int) $_GET['id_joueur'];
}
else{
    exit('id_joueur needed');
}

$j = new Joueur($id_joueur);
//$j->credits++;
echo '<p>'.$j->getNom().' '.$j->credits.'$</p>';

?>

