<?php
if(empty($_POST['perso'])){
    exit('perso needed');
}
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$id = (int) $_POST['perso'];

$o = new Perso($id);
if($Joueur->id != $o->joueur->id){//verification que le perso nous appartient bien:
 exit("Ce perso ne vous appartient pas");
}

$erreur = $o->startDiplomatie((int) ($_POST['action'] ?? 0));
if($erreur !== null){
    Tools::addMessage($erreur);
}
?>

<script>
    location.href='?p=affichePerso&id=<?= $id ?>';
    </script>
    


