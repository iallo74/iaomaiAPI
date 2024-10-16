<?php
/* INALTERATO */
include_once("_include/_testata.php");

if($b64)$JSNPOST=urldecode(base64_decode($JSNPOST));
else $JSNPOST=stripslashes(conv($JSNPOST));
$vars=json_decode($JSNPOST);
$idUtente=$vars->idUtente;
$fileAvatar=$vars->fileAvatar;

$cartellaUtente = "../__files_utenti/files/".$idUtente."/";
if(!file_exists($cartellaUtente))mkdir($cartellaUtente);
$fileAvatar = $cartellaUtente.$fileAvatar;

$cont = '';
if(file_exists($fileAvatar)){
	$cont = "data:image/jpeg;base64,".base64_encode(file_get_contents($fileAvatar));
}
die($cont);
?>