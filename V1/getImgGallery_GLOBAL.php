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

$cartellaUtente = "../__files_utenti/files/".$idUtente."/";
if(!file_exists($cartellaUtente))mkdir($cartellaUtente);

$list = [];
if(!$online){
	foreach($foto as $idFile){
		$idFoto = $idFile;
		$idFile = str_replace("foto_","file_",$idFile);
		
		unset($res);
		$file_dwnl = $cartellaUtente.$idFile.".json";
		if(!file_exists($file_dwnl))$file_dwnl = "../__files_utenti/files/frv/".$idFile.".json";
		if(file_exists($file_dwnl)){
			$row = json_decode(file_get_contents($file_dwnl));
			$res = json_decode('{}');
			$res -> idFoto = $idFoto;
			$res -> imgMini = $row -> imgMini;
			$res -> name = $row -> name;
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
			$idFoto = str_replace("file_","foto_",$idFile);
			$ext = $pF[1];
			if(	!is_dir($cartellaUtente.$file) &&
				substr($file,0,5)=='file_' &&
				$ext=='json' ){
					
				$file_dwnl = $cartellaUtente.$idFile.".json";
				if(!file_exists($file_dwnl))$file_dwnl = "../__files_utenti/files/frv/".$idFile.".json";
				if(file_exists($file_dwnl)){
					$row = json_decode(file_get_contents($file_dwnl));
					$res = json_decode('{}');
					$res -> idFoto = $idFoto;
					$res -> imgMini = $row -> imgMini;
					$res -> name = $row -> name;
					array_push($list,$res);
				}
			}
		}
	}
}
die(json_encode($list));
?>