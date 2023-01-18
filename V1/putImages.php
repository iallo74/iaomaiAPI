<?php
include_once("_include/_testata.php");

if($b64)$JSNPOST=urldecode(base64_decode($JSNPOST));
else $JSNPOST=stripslashes(conv($JSNPOST));
$vars=json_decode($JSNPOST);
$idUtente=$vars->idUtente;
$FOTO=json_decode(base64_decode($vars->FOTO));
	
for($k=0;$k<count($FOTO);$k++){
	$foto = json_decode("{}");
	$foto -> imgMini = $FOTO[$k]->imgMini;
	$foto -> imgBig = $FOTO[$k]->imgBig;
	$cartellaUtente = "../__files_utenti/immagini/".$idUtente."/";
	if(!file_exists($cartellaUtente))mkdir($cartellaUtente);
	if($foto -> imgMini && $foto -> imgBig && strpos($foto -> imgBig,"data:image")==0)salva_file($cartellaUtente.$FOTO[$k]->idFoto.".json", json_encode($foto));
}
			
			
?>