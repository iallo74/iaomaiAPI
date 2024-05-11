<?php
/* AGGIORNATO */
include_once("_include/_testata.php");


if($b64)$JSNPOST=base64_decode($JSNPOST);
else $JSNPOST=conv($JSNPOST);
$vars=json_decode($JSNPOST);
$idUtente = $vars -> idUtente;
$JSNPOST = $vars -> JSNPOST;

if($b64)$JSNPOST=base64_decode($JSNPOST);
else $JSNPOST=conv($JSNPOST);
$vars=json_decode($JSNPOST);
$files = json_decode("{}");
$files -> name = $vars -> n;
$files -> imgMini = $vars -> e;
$files -> imgBig = $vars -> F;
$files -> type = $vars -> t ? $vars -> t : $vars -> e;
$cartellaUtente = "../__files_utenti/files/".$idUtente."/";
if(!file_exists($cartellaUtente))mkdir($cartellaUtente);
//salva_file($cartellaUtente."file_".$vars->idFile.".json", json_encode($files));
salva_file($cartellaUtente."file_".$vars->idFile."_".$files -> type.".json", json_encode($files));


$files -> imgBig = "";
$files -> idFile = $vars->idFile;
die(json_encode($files));

?>