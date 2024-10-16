<?php
include_once("_include/_testata.php");


if($b64)$JSNPOST=base64_decode($JSNPOST);
else $JSNPOST=conv($JSNPOST);
$vars=json_decode($JSNPOST);
$idUtente = $vars -> idUtente;
$JSNPOST = $vars -> JSNPOST;

if($b64)$JSNPOST=base64_decode($JSNPOST);
else $JSNPOST=conv($JSNPOST);
$vars=json_decode($JSNPOST);

$foto = json_decode("{}");
$foto -> name = $vars -> n;
$foto -> imgMini = $vars -> e;
$foto -> imgBig = $vars -> F;
$cartellaUtente = "../__files_utenti/files/".$idUtente."/";
if(!file_exists($cartellaUtente))mkdir($cartellaUtente);
file_put_contents($cartellaUtente."file_".$vars->idFoto.".json", json_encode($foto));


$foto -> imgBig = "";
$foto -> idFoto = $vars->idFoto;
die(json_encode($foto));

?>