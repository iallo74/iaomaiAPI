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


$n = intval("0".$_GET["n"]);
echo $n."<br>";
$tot=0;
$default_dir = "../__files_utenti/backups/";
$dp=opendir($default_dir);
while($dir=readdir($dp)){
	if($dir[0]!='_' && $dir[0]!='.' && $dir[0]!='-'){
		if(is_dir($default_dir.$dir)){
			//echo "----".$dir.chr(10);
			$default_dir2 = $default_dir.$dir."/";
			$dp2=opendir($default_dir2);
			while($file=readdir($dp2)){
				if(substr($file,0,3)=='BKP'){
					if($tot==$n){
						echo '----> ';




						$BKP = json_decode(leggi_file($default_dir2.$file),true);
						
						$DDDBBB = json_decode(rawurldecode(base64_decode($BKP["JSNPOST"])),true);
						$NEW = array();
						
						foreach($DDDBBB as $d => $cont){
							$NEW[$d] = array();
							if($d!='ricerche' && $d!='procedure'){
								foreach($cont as $i => $cont2){
									$NEW[$d][$i] = $cont2;
									foreach($cont2 as $t => $txt){
										if(gettype($txt)=='string'){
											if( substr($txt,0,3)=="[@]" ){
												$NEW[$d][$i][$t] = sanitizeUTF16($txt);
											}else{
												if($t=='numeroTsubo')$NEW[$d][$i]["numeroPunto"] = $txt;
												$NEW[$d][$i][$t] = $txt;
											}
										}
										if($t == 'trattamenti' || $t == 'saldi'){
											foreach($txt as $i2 => $cont3){
												$NEW[$d][$i][$t][$i2] = $cont3;
												foreach($cont3 as $t2 => $txt2){
													$new_txt = $txt2;
													if(gettype($txt2)=='string'){
														if( substr($txt2,0,3)=="[@]" ){
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
						$BKP["JSNPOST"] = base64_encode(rawurlencode(json_encode($NEW)));
						if(!copy($default_dir2.$file,$default_dir2."_".str_replace(".json","-OLD.json",$file)))echo 'FALLITO';
						salva_file($default_dir2.$file,json_encode($BKP));









					}
					echo $default_dir2.$file."<br>";
					$tot++;
				}
			}
		}
	}
}
echo '<br><a href="bkp2.php?TEST=Gh$hgasd1!&n='.($n+1).'">GO</a>';


/*
$file = "../__files_utenti/backups/1/BKP_b64-1676500055.json";
$BKP = json_decode(leggi_file($file),true);

$DDDBBB = json_decode(rawurldecode(base64_decode($BKP["JSNPOST"])),true);
$NEW = array();

foreach($DDDBBB as $d => $cont){
	$NEW[$d] = array();
	if($d!='ricerche' && $d!='procedure'){
		foreach($cont as $i => $cont2){
			$NEW[$d][$i] = $cont2;
			foreach($cont2 as $t => $txt){
				if(gettype($txt)=='string'){
					if( substr($txt,0,3)=="[@]" ){
						$NEW[$d][$i][$t] = sanitizeUTF16($txt);
                    }else{
						$NEW[$d][$i][$t] = $txt;
					}
				}
				if($t == 'trattamenti' || $t == 'saldi'){
					foreach($txt as $i2 => $cont3){
						$NEW[$d][$i][$t][$i2] = $cont3;
						foreach($cont3 as $t2 => $txt2){
							$new_txt = $txt2;
                            if(gettype($txt2)=='string'){
                                if( substr($txt2,0,3)=="[@]" ){
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
$BKP["JSNPOST"] = base64_encode(rawurlencode(json_encode($NEW)));
salva_file("../__files_utenti/backups/1/BKP_b64-1676500055-REV.json",json_encode($BKP));


*/




?>