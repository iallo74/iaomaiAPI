<?php
/* AGGIORNATO */
include_once("_include/_testata.php");

if($b64)$JSNPOST=urldecode(base64_decode($JSNPOST));
else $JSNPOST=stripslashes(conv($JSNPOST));
$vars=json_decode($JSNPOST);
$idUtente=$vars->idUtente;
$FILES=json_decode(base64_decode($vars->FILES));
	
for($k=0;$k<count($FILES);$k++){
	$file = json_decode("{}");
	$file -> imgMini = $FILES[$k]->imgMini;
	$file -> imgBig = $FILES[$k]->imgBig;
	$file -> type = "img";
	$cartellaUtente = "../__files_utenti/files/".$idUtente."/";
	if(!file_exists($cartellaUtente))mkdir($cartellaUtente);
	if($file -> imgMini && $file -> imgBig && strpos($file -> imgBig,"data:image")==0){
		//salva_file($cartellaUtente.$FILES[$k]->idFile.".json", json_encode($file));
		salva_file($cartellaUtente.$FILES[$k]->idFile."_img.json", json_encode($file));
	}
}
			
			
?>