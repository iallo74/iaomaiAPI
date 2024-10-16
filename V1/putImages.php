<?php
include_once("_include/_testata.php");

if($b64)$JSNPOST=urldecode(base64_decode($JSNPOST));
else $JSNPOST=stripslashes(conv($JSNPOST));
$vars=json_decode($JSNPOST);
$idUtente=$vars->idUtente;
$FILES=json_decode(base64_decode($vars->FOTO));

	
for($k=0;$k<count($FILES);$k++){
	$file_name = str_replace("foto_","file_",$FILES[$k]->idFoto);
	$file = json_decode("{}");
	$file -> imgMini = $FILES[$k]->imgMini;
	$file -> imgBig = $FILES[$k]->imgBig;
	$cartellaUtente = "../__files_utenti/files/".$idUtente."/";
	if(!file_exists($cartellaUtente))mkdir($cartellaUtente);
	if($file -> imgMini && $file -> imgBig && strpos($file -> imgBig,"data:image")==0)file_put_contents($cartellaUtente.$file_name.".json", json_encode($file));
}
			
			
?>