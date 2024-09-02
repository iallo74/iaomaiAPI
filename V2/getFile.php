<?php
/* AGGIORNATO */
include_once("_include/_testata.php");

if($b64)$JSNPOST=urldecode(base64_decode($JSNPOST));
else $JSNPOST=stripslashes(conv($JSNPOST));
$vars=json_decode($JSNPOST);
$idFile=$vars->idFile;
$idU=$vars->idU;



//die(leggi_file("../__files_utenti/files/".$idU."/file_".$idFile.".json"));


$folder = "../__files_utenti/files/".$idU."/";
$dp=opendir($folder);
while($file=readdir($dp)){
	if ($file[0]!='_' && $file[0]!='.' && $file[0]!='-'){
		if(preg_match("/file_".$idFile."[^\.]*\.json/",$file)){
			die(leggi_file($folder.$file));
		}
	}
}

?>