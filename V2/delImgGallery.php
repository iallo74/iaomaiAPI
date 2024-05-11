<?php
/* AGGIORNATO */

include_once("_include/_testata.php");

if($b64)$JSNPOST=urldecode(base64_decode($JSNPOST));
else $JSNPOST=stripslashes(conv($JSNPOST));
$vars=json_decode($JSNPOST);
$iU=$vars->iU;
$idFile=$vars->idFile;
		
/*$file = "../__files_utenti/files/".$iU."/".$idFile.".json";
if(file_exists($file))unlink($file);
die("OK");*/

$folder = "../__files_utenti/files/".$iU."/";
$dp=opendir($folder);
while($file=readdir($dp)){
	if ($file[0]!='_' && $file[0]!='.' && $file[0]!='-'){
		if(preg_match("/".$idFile."[^\.]*\.json/",$file)){
			unlink($folder.$file);
		}
	}
}
die("OK");





?>