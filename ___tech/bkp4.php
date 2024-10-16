<?php
if($_GET["TEST"]!='Gh$hgasd1!')die("Access denied");
include_once("../../_include/funzioni.php");


$file = "../__files_utenti/backups/1/BKP_b64-1676500055.json";
$BKP = json_decode(file_get_contents($file),true);

$DDDBBB = json_decode(rawurldecode(base64_decode($BKP["JSNPOST"])),true);

//while(list($d,$cont) = each($DDDBBB)){
foreach($DDDBBB as $d=>$cont){
	//while(list($i,$cont2) = each($cont)){
	foreach($cont as $i=>$cont2){
		$NEW[$d][$i] = $cont2;
		//while(list($t,$txt) = each($cont2)){ // elenco le etichette
		foreach($cont2 as $t=>$txt){ // elenco le etichette
			if($t=='gallery')echo str_replace("idFoto","idFile",str_replace("foto_","file_",$txt))."<br>";
			if($t == 'trattamenti' || $t == 'saldi'){
				//while(list($i2,$cont3) = each($txt)){
				foreach($txt as $i2=>$cont3){
					//while(list($t2,$txt2) = each($cont3)){
					foreach($cont3 as $t2=>$txt2){
						if($t2=='gallery')echo str_replace("idFoto","idFile",str_replace("foto_","file_",$txt2))."<br>";
					}
				}
			}
		}
	}
}

?>