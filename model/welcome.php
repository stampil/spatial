<?php
if($_SESSION['Sid']){
    header('Location: ?p=tableauBord');
    exit();
}
?>
<h2><a href="?p=inscription">Inscription</a></h2>
<h2><a href="?p=connection">Connection</a></h2>
