<?php
if(!empty($_SESSION['Sid'])){
    header('Location: ?p=tableauBord');
    exit();
}
?>
<a href="?p=inscription">Inscription</a>
<a href="?p=connection">Connection</a>
