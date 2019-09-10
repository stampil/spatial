<?php
if(!empty($_GET['perso'])){
    $id = $_GET['perso'];
}
else{
    exit('perso needed');
}

$o = new Perso($id);
if($Joueur->id != $o->joueur->id){//verification que le perso nous appartient bien:
 exit("Ce joueur ne vous appartient pas");
}

$o->setDiplomatie();
?>

<script>
    location.href='?p=affichePerso&id=<?= $id ?>';
    </script>
    


