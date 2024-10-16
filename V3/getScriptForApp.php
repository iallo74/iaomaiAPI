<?php
include_once("_include/_testata.php");

if($b64)$JSNPOST=urldecode(base64_decode($JSNPOST));
else $JSNPOST=stripslashes(conv($JSNPOST));
$vars=json_decode($JSNPOST);
$nomeScript = $vars->nomeScript;

$percorso = "../../app/".file_get_contents("../../app/__actVer.txt")."/";
$file = '';
function makeUrl( $prof = NULL ){
	$file = '';
	$pF = explode("_",$GLOBALS["nomeScript"]);
	for($p=0;$p<count($pF);$p++){
		$file .= $pF[$p];
		if($p<count($pF)-1){
			if($p<count($pF)-($prof+1))$file .= '/';
			//else if($p==count($pF)-2)$file .= '.';
			else $file .= '_';
		}
	}
	return $file;
}
$file = makeUrl(1);
if(!file_exists($percorso.$file)){
	$file = makeUrl(2);
}
if(!file_exists($percorso.$file)){
	$file = makeUrl(3);
}
if(!file_exists($percorso.$file)){
	$file = makeUrl(4);
}
if(file_exists($percorso.$file))die(base64_encode(file_get_contents($percorso.$file)));
else die('404 '.base64_encode($percorso.$file));

?>