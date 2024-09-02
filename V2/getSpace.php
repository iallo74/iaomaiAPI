<?php
include_once("_include/_testata.php");
include_once("_config.php");

if($b64)$JSNPOST=urldecode(base64_decode($JSNPOST));
else $JSNPOST=stripslashes(conv($JSNPOST));

$vars=json_decode($JSNPOST);
$idUtente=$vars->idUtente;
	
$folder = "../__files_utenti/files/".$idUtente."/";
$images_amount = 0;
$files_amount = 0;
$dp=opendir($folder);
while($file=readdir($dp)){
	if ($file[0]!='_' && $file[0]!='.' && $file[0]!='-'){
		if(!is_dir($folder.$file)){
			$cont = json_decode(file_get_contents($folder.$file),true);
			$type = $cont["type"]."";
			if($type!='vid'){
				$size = filesize($folder.$file);
				if(/*!$type || */$type=='img' || strlen($cont["imgMini"])>10)$images_amount += $size;
				else $files_amount += $size;
			}
		}
	}
}
die(json_encode(["images_amount" => $images_amount, "files_amount" => $files_amount, "images_quota" => $images_quota, "files_quota" => $files_quota]));
?>