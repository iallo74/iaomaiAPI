<?php
if($_GET["TEST"]!='Gh$hgasd1!')die("Access denied");
include_once("../../_include/funzioni.php");
include_once("../../_include/file.php");




include_once('../../_include/LZCompressor/LZContext.php');
include_once('../../_include/LZCompressor/LZData.php');
include_once('../../_include/LZCompressor/LZReverseDictionary.php');
include_once('../../_include/LZCompressor/LZString.php');
include_once('../../_include/LZCompressor/LZUtil.php');
include_once('../../_include/LZCompressor/LZUtil16.php');
use \LZCompressor\LZString as LZ;

function sanitizeUTF16( $txt ){
	if(substr($txt,0,3)=='[@]'){
		$txt = str_replace("[@]","",$txt);
		$txt = mb_convert_encoding($txt, "UTF-16", "UTF-8");
		$txt = LZ::decompressFromUTF16($txt);
	}
	return $txt;
}





$file = "../__files_utenti/backups/1/BKP_b64-1676500055.json";
$BKP = json_decode(leggi_file($file),true);

$DDDBBB = json_decode(rawurldecode(base64_decode($BKP["JSNPOST"])),true);
$NEW = array();

while(list($d,$cont) = each($DDDBBB)){
	$NEW[$d] = array();
	if($d!='ricerche' && $d!='procedure'){
		//echo $d.chr(10);
		while(list($i,$cont2) = each($cont)){
			$NEW[$d][$i] = $cont2;
			while(list($t,$txt) = each($cont2)){ // elenco le etichette
				if(gettype($txt)=='string'){
					if( substr($txt,0,3)=="[@]" ){
						$NEW[$d][$i][$t] = sanitizeUTF16($txt);
                    }else{
						//if($t=='numeroTsubo')$NEW[$d][$i]["numeroPunto"] = $txt;
						$NEW[$d][$i][$t] = $txt;
					}
				}
				if($t == 'trattamenti' || $t == 'saldi'){
					while(list($i2,$cont3) = each($txt)){
						$NEW[$d][$i][$t][$i2] = $cont3;
						while(list($t2,$txt2) = each($cont3)){
							$new_txt = $txt2;
                            if(gettype($txt2)=='string'){
                                if( substr($txt2,0,3)=="[@]" ){
                                   // echo sanitizeUTF16($txt2);
									$new_txt = sanitizeUTF16($txt2);
                                    $NEW[$d][$i][$t][$i2][$t2] = $new_txt;
                                }
                            }
                           	if($t2=='puntiTsuboMap')$NEW[$d][$i][$t][$i2]["puntiMTC"] = $new_txt;
                            if($t2=='puntiAuriculoMap')$NEW[$d][$i][$t][$i2]["puntiAuricolari"] = $new_txt;
                        }
                    }
				}
			}
		}
		
	}else{
		$NEW[$d] = $cont;
	}
}
print_r($NEW);
/*$BKP["JSNPOST"] = base64_encode(rawurlencode(json_encode($NEW)));


salva_file("../__files_utenti/backups/1/BKP_b64-1676500055-REV.json",json_encode($BKP));*/
?>