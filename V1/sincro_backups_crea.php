<?php
include_once("_include/_testata.php");

if($b64)$JSNPOST=base64_decode($JSNPOST);
else $JSNPOST=conv($JSNPOST);
$vars=json_decode($JSNPOST);
$idUtente = $vars -> idUtente;
$JSNPOST = $vars -> JSNPOST;


if($b64)$JSNPOST=base64_decode($JSNPOST);
else $JSNPOST=conv($JSNPOST);
$JSNPOST=urldecode($JSNPOST);

$cartellaUtente = "../__files_utenti/backups/".$idUtente."/";
if(!file_exists($cartellaUtente))mkdir($cartellaUtente);

$nomeBackup = "BKP_b64-".time();
file_put_contents($cartellaUtente.$nomeBackup.".json",stripslashes($JSNPOST));

if($errore)die("404");
else die($nomeBackup);

?>