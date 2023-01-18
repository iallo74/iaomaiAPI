<?php
include_once("_include/_testata.php");

if($b64)$JSNPOST=urldecode(base64_decode($JSNPOST));
else $JSNPOST=stripslashes(conv($JSNPOST));
$vars=json_decode($JSNPOST);
$idUtente = $vars->idUtente;
$online = $vars->online;
$JSNPOST = $vars->JSNPOST;

if($b64)$JSNPOST=base64_decode($JSNPOST);
else $JSNPOST=conv($JSNPOST);
$foto = json_decode(urldecode($JSNPOST));

$cartellaUtente = "../__files_utenti/immagini/".$idUtente."/";
if(!file_exists($cartellaUtente))mkdir($cartellaUtente);

$list = [];
if(!$online){
	foreach($foto as $idFoto){
		unset($res);
		$row = json_decode(leggi_file($cartellaUtente.$idFoto.".json"));
		$res = json_decode('{}');
		$res -> idFoto = $idFoto;
		$res -> imgMini = $row -> imgMini;
		$res -> name = $row -> name;
		if($res -> imgMini==NULL)$res -> imgMini = '404';
		array_push($list,$res);
	}
}else{
	$dp=opendir($cartellaUtente);
	while ($file=readdir($dp)){
		if ($file[0]!='_' && $file[0]!='.' && $file[0]!='-'){
			$pF = explode(".",$file);
			$idFoto = $pF[0];
			$ext = $pF[1];
			if(	!is_dir($cartellaUtente.$file) &&
				substr($file,0,5)=='foto_' &&
				$ext=='json' ){
					
				$row = json_decode(leggi_file($cartellaUtente.$idFoto.".json"));
				$res = json_decode('{}');
				$res -> idFoto = $idFoto;
				$res -> imgMini = $row -> imgMini;
				$res -> name = $row -> name;
				array_push($list,$res);
			}
		}
	}
}
die(json_encode($list));
?>