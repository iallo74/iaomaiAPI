<?php
include_once("_include/_testata.php");

if($b64)$JSNPOST=base64_decode($JSNPOST);
else $JSNPOST=conv($JSNPOST);
$vars=json_decode($JSNPOST);
$idUtente=$vars->idUtente;
	


		$JSNPOST = array();
		$cartellaUtente = "../__files_utenti/files/".$idUtente."/";
		if(!file_exists($cartellaUtente))mkdir($cartellaUtente);
		$dp=opendir($cartellaUtente);
		while ($file=readdir($dp)){
			if ($file[0]!='.' && $file[0]!='-'){
				$pF = explode(".",$file);
				$ext = $pF[1];
				if(	!is_dir($cartellaUtente.$file) &&
					substr($file,0,5)=='file_' &&
					$ext=='json' ){
						array_push($JSNPOST,$file);
				}
				if(	!is_dir($cartellaUtente.$file) &&
					substr($file,0,2)=='__' &&
					$ext=='jpg' ){
						array_push($JSNPOST,$file);
				}
			}
		}
		
		$cartellaUtente = "../__files_utenti/backups/".$idUtente."/";
		if(!file_exists($cartellaUtente))mkdir($cartellaUtente);
		$dp=opendir($cartellaUtente);
		while ($file=readdir($dp)){
			if ($file[0]!='_' && $file[0]!='.' && $file[0]!='-'){
				$pF = explode(".",$file);
				$ext = $pF[1];
				if(	!is_dir($cartellaUtente.$file) &&
					substr($file,0,3)=='BKP' &&
					$ext=='json' ){
						array_push($JSNPOST,$file);
				}
			}
		}
		die(json_encode($JSNPOST));

?>