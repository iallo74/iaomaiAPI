<?php
include_once("_include/_testata.php");

if($b64)$JSNPOST=base64_decode($JSNPOST);
else $JSNPOST=conv($JSNPOST);
$vars=json_decode($JSNPOST);
$idUtente = $vars -> idUtente;
$f = $vars -> f;


$cartellaUtente = "../__files_utenti/backups/".$idUtente."/";
if(!file_exists($cartellaUtente))mkdir($cartellaUtente);

rename($cartellaUtente.$f.".json",$cartellaUtente."_".$f.".json");

if($errore)die("404");
else die("OK");

?>