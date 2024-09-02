<?php
include_once("_include/_testata.php");

if($b64)$JSNPOST=urldecode(base64_decode($JSNPOST));
else $JSNPOST=stripslashes(conv($JSNPOST));
$vars=json_decode($JSNPOST);
$iU=$vars->iU;
$idFoto=$vars->idFoto;

$idFile = str_replace("foto_","file_",$idFoto);
		
$file = "../__files_utenti/files/".$iU."/".$idFile.".json";
if(file_exists($file))unlink($file);
die("OK");

?>