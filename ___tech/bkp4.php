<?php
if($_GET["TEST"]!='Gh$hgasd1!')die("Access denied");
include_once("../../_include/funzioni.php");
include_once("../../_include/file.php");


$file = "../__files_utenti/backups/1/BKP_b64-1676500055.json";
$BKP = json_decode(leggi_file($file),true);

$DDDBBB = json_decode(rawurldecode(base64_decode($BKP["JSNPOST"])),true);

while(list($d,$cont) = each($DDDBBB)){
	while(list($i,$cont2) = each($cont)){
		$NEW[$d][$i] = $cont2;
		while(list($t,$txt) = each($cont2)){ // elenco le etichette
			if($t=='gallery')echo str_replace("idFoto","idFile",str_replace("foto_","file_",$txt))."<br>";
			if($t == 'trattamenti' || $t == 'saldi'){
				while(list($i2,$cont3) = each($txt)){
					while(list($t2,$txt2) = each($cont3)){
						if($t2=='gallery')echo str_replace("idFoto","idFile",str_replace("foto_","file_",$txt2))."<br>";
					}
				}
			}
		}
	}
}

?>