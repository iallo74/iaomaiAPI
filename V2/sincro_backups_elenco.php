<?php
/* INALTERATO */
include_once("_include/_testata.php");

if($b64)$JSNPOST=base64_decode($JSNPOST);
else $JSNPOST=conv($JSNPOST);
$vars=json_decode($JSNPOST);
$idUtente = $vars -> idUtente;

$cartellaUtente = "../__files_utenti/backups/".$idUtente."/";
if(!file_exists($cartellaUtente))mkdir($cartellaUtente);

$srv=array();
$dp=opendir($cartellaUtente);
$n = 0;
while ($file=readdir($dp)){
	if ($file[0]!='_' && $file[0]!='.' && $file[0]!='-'){
		if(!is_dir($cartellaUtente.$file) && substr($file,0,3)=='BKP'){
			$bkp = json_decode(file_get_contents($cartellaUtente.$file));
			
			$srv[$n] = array();
			$srv[$n]["name"] = $file;
			$srv[$n]["title"] = $bkp -> title;
			$n++;
		}
	}
}
$tot_srv=count($srv);
rsort($srv);

die(json_encode($srv));

?>