<?php
if(!empty($_GET['id_systeme'])){
    $id_systeme = $_GET['id_systeme'];
}
else{
    exit('id_systeme needed');
}

$s = new Systeme($id_systeme);

echo $s->nom?$s->nom:'Systeme'.$s->id;


?>


