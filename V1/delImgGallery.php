<?php
include_once("_include/_testata.php");

if($b64)$JSNPOST=urldecode(base64_decode($JSNPOST));
else $JSNPOST=stripslashes(conv($JSNPOST));
$vars=json_decode($JSNPOST);
$iU=$vars->iU;
$idFoto=$vars->idFoto;
		
$file = "../__files_utenti/immagini/".$iU."/".$idFoto.".json";
if(file_exists($file))unlink($file);
die("OK");

?>