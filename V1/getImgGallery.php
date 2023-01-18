<?php
include_once("_include/_testata.php");

if($b64)$JSNPOST=urldecode(base64_decode($JSNPOST));
else $JSNPOST=stripslashes(conv($JSNPOST));
$vars=json_decode($JSNPOST);
$iU=$vars->iU;
$idFoto=$vars->idFoto;
$big=$vars->big;
$n=$vars->n;
		
$cartellaUtente = "../__files_utenti/immagini/".$iU."/";
if(!file_exists($cartellaUtente))mkdir($cartellaUtente);
if($idFoto){
	$row = json_decode(leggi_file($cartellaUtente.$idFoto.".json"));
	$res = json_decode('{}');
	$res -> n = $n;
	$res -> idFoto = $idFoto;
	$res -> name = $row -> name;
	if(!$big)$res -> imgMini = $row -> imgMini;
	else{
		if($row -> imgBig && $row -> imgBig!='404')$res -> imgBig = $row -> imgBig;
		else $res -> imgBig = $row -> imgMini;
	}
	die(json_encode($res));
}else die("404");

?>