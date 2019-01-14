<?php 

$montant = 5000;
$chemin_access = $_SERVER['HTTP_HOST']."/Cw_Application/Layers/Bibliotheques/PROCESS_CW/index.php?r=Encaissement/Timbre/Calcul&MONTANT=".$montant;
echo $chemin_access;

 ?>