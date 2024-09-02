<?php
/* AGGIORNATO */
include_once("_include/_testata.php");

if($b64)$JSNPOST=urldecode(base64_decode($JSNPOST));
else $JSNPOST=stripslashes(conv($JSNPOST));
$vars=json_decode($JSNPOST);
$iU=$vars->iU;
$idFile=$vars->idFile;
$big=$vars->big;
$n=$vars->n;

$cartellaUtente = "../__files_utenti/files/".$iU."/";
if(!file_exists($cartellaUtente))mkdir($cartellaUtente);
if($idFile){
	
	//$file = $cartellaUtente.$idFile.".json";
	$dp=opendir($cartellaUtente);
	while($f=readdir($dp)){
		if ($f[0]!='_' && $f[0]!='.' && $f[0]!='-'){
			if(preg_match("/".$idFile."[^\.]*\.json/",$f)){
				$file = $cartellaUtente.$f;
			}
		}
	}
	

	if(!file_exists($file))$file = "../__files_utenti/files/frv/".$idFile.".json";
	if(!file_exists($file))die("404");
	$row = json_decode(leggi_file($file));
	$res = json_decode('{}');
	$res -> n = $n;
	$res -> idFile = $idFile;
	$res -> name = $row -> name;
	if(!$big)$res -> imgMini = $row -> imgMini;
	else{
		if($row -> imgBig && $row -> imgBig!='404')$res -> imgBig = $row -> imgBig;
		else $res -> imgBig = $row -> imgMini;
	}
	die(json_encode($res));
}else die("404");

?>