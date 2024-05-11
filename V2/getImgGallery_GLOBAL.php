<?php
/* AGGIORNATO */
include_once("_include/_testata.php");

if($b64)$JSNPOST=urldecode(base64_decode($JSNPOST));
else $JSNPOST=stripslashes(conv($JSNPOST));
$vars=json_decode($JSNPOST);
$idUtente = $vars->idUtente;
$online = $vars->online;
$JSNPOST = $vars->JSNPOST;

if($b64)$JSNPOST=base64_decode($JSNPOST);
else $JSNPOST=conv($JSNPOST);
$files = json_decode(urldecode($JSNPOST));

$cartellaUtente = "../__files_utenti/files/".$idUtente."/";
if(!file_exists($cartellaUtente))mkdir($cartellaUtente);

$list = [];
if(!$online){
	foreach($files as $idFile){
		unset($res);
		$file_dwnl = $cartellaUtente.$idFile.".json";
		if(!file_exists($file_dwnl))$file = "../__files_utenti/files/frv/".$idFile.".json";
		if(file_exists($file_dwnl)){
			$row = json_decode(leggi_file($file_dwnl));
			$res = json_decode('{}');
			$res -> idFile = $idFile;
			$res -> imgMini = $row -> imgMini;
			$res -> name = $row -> name;
			$res -> type = ($row -> type) ? $row -> type : ((strlen($row -> imgMini)<10) ? $row -> imgMini : 'img');
			if($res -> imgMini==NULL)$res -> imgMini = '404';
			array_push($list,$res);
		}
	}
}else{
	$dp=opendir($cartellaUtente);
	while ($file=readdir($dp)){
		if ($file[0]!='_' && $file[0]!='.' && $file[0]!='-'){
			$pF = explode(".",$file);
			$idFile = $pF[0];
			$ext = $pF[1];
			if(	!is_dir($cartellaUtente.$file) &&
				substr($file,0,5)=='file_' &&
				$ext=='json' ){
					
				//$file_dwnl = $cartellaUtente.$idFile.".json";
				$file_dwnl = $cartellaUtente.$file;
				if(!file_exists($file_dwnl))$file = "../__files_utenti/files/frv/".$idFile.".json";
				if(file_exists($file_dwnl)){
					//$file = $cartellaUtente.$idFile.".json";
					$row = json_decode(leggi_file($file_dwnl));
					$res = json_decode('{}');
					$res -> idFile = $idFile;
					$res -> imgMini = $row -> imgMini;
					$res -> name = $row -> name;
					$res -> type = ($row -> type) ? $row -> type : ((strlen($row -> imgMini)<10) ? $row -> imgMini : 'img');
					array_push($list,$res);
				}
			}
		}
	}
}
die(json_encode($list));
?>