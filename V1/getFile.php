<?php
include_once("_include/_testata.php");

if($b64)$JSNPOST=urldecode(base64_decode($JSNPOST));
else $JSNPOST=stripslashes(conv($JSNPOST));
$vars=json_decode($JSNPOST);
$idFoto=$vars->idFoto;
$idU=$vars->idU;

die(leggi_file("../__files_utenti/immagini/".$idU."/foto_".$idFoto.".json"));
?>