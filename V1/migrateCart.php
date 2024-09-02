<?php
include_once("_include/_testata.php");

if($b64)$JSNPOST=urldecode(base64_decode($JSNPOST));
else $JSNPOST=stripslashes(conv($JSNPOST));
$vars=json_decode($JSNPOST);
$idUtente=$vars->idUtente;
$JSNPOST = $vars->JSNPOST;


$JSNPOST = json_decode(base64_decode($JSNPOST),true);
while(list($val,$cont) = each($JSNPOST)){
	$cartellaUtente = "../__files_utenti/".$val."/".$idUtente."/";
	if(!file_exists($cartellaUtente))mkdir($cartellaUtente);
	
	while(list($val2,$row) = each($cont)){
		$name = $row["name"];
		$pF = explode(".",$name);
		$ext = $pF[1];
		if(substr($name,0,5)=='file_' || substr($file,0,3)=='BKP'){
			$file = json_encode($row["cont"]);
		}
		if(substr($name,0,2)=='__' && $ext=='jpg'){
			$file = base64_decode(str_replace("data:image/jpeg;base64,","",$row["cont"]));
		}
		salva_file($cartellaUtente.$name,$file);
	}
}

?>