<?php
include_once("_include/_testata.php");

if($b64)$JSNPOST=base64_decode($JSNPOST);
else $JSNPOST=conv($JSNPOST);
$vars=json_decode($JSNPOST);
$idUtente=$vars->idUtente;
$imgAvatar = $vars->imgAvatar;
$logoAzienda = $vars->logoAzienda;
	
$cartellaUtente = "../__files_utenti/immagini/".$idUtente."/";
if(!file_exists($cartellaUtente))die("false");
else{
	if(!file_exists($cartellaUtente."___migrati.txt"))die("false");
	else die("true");
}

?>