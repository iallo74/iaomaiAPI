	<?php
if($_GET["TEST"]!='Gh$hgasd1!')die("Access denied");
include_once("../../_include/funzioni.php");



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




						$BKP = json_decode(file_get_contents($default_dir2.$file),true);
						
						$DDDBBB = json_decode(rawurldecode(base64_decode($BKP["JSNPOST"])),true);
						$NEW = array();
						
						//while(list($d,$cont) = each($DDDBBB)){
						foreach($DDDBBB as $d=>$cont){
							$NEW[$d] = array();
							if($d!='ricerche' && $d!='procedure'){
								//while(list($i,$cont2) = each($cont)){
								foreach($cont as $i=>$cont2){
									$NEW[$d][$i] = $cont2;
									//while(list($t,$txt) = each($cont2)){ // elenco le etichette
									foreach($cont2 as $t=>$txt){ // elenco le etichette
										if($t=='gallery')$txt = str_replace("idFoto","idFile",str_replace("foto_","file_",$txt));
										$NEW[$d][$i][$t] = $txt;
										if($t == 'trattamenti' || $t == 'saldi'){
											//while(list($i2,$cont3) = each($txt)){
											foreach($txt as $i2=>$cont3){
												//while(list($t2,$txt2) = each($cont3)){
												foreach($cont3 as $t2=>$txt2){
													if($t2=='gallery')$txt2 = str_replace("idFoto","idFile",str_replace("foto_","file_",$txt2));
													$NEW[$d][$i][$t][$i2][$t2] = $txt2;
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
						file_put_contents($default_dir2.$file,json_encode($BKP));

					}
					echo $default_dir2.$file."<br>";
					$tot++;
				}
			}
		}
	}
}
echo '<br><a href="bkp5.php?TEST=Gh$hgasd1!&n='.($n+1).'">GO</a>';

?>