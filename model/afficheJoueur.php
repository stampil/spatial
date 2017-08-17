<?php
if(!empty($_GET['id_joueur'])){
    $id_joueur = $_GET['id_joueur'];
}
else{
    exit('id_joueur needed');
}

$j = new Joueur($id_joueur);
$j->credits++;
$j->save();
echo '<p>'.$j->nom.' '.$j->credits.'$</p>';


?>


