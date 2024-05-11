<?php
if($_GET["TEST"]!='Gh$hgasd1!')die("Access denied");
include_once("../../_include/funzioni.php");
include_once("../../_include/file.php");

$files_frv = [];
$default_dir = "../__files_utenti/files/frv/";
$dp=opendir($default_dir);
while($file=readdir($dp)){
	if($file[0]!='_' && $file[0]!='.' && $file[0]!='-'){
		if(substr($file,0,5)=='file_'){
			array_push($files_frv,$file);
		}
	}
}

$default_dir = "../__files_utenti/files/";
$dp=opendir($default_dir);
while($dir=readdir($dp)){
	if($dir[0]!='_' && $dir[0]!='.' && $dir[0]!='-' && $dir!='frv'){
		if(is_dir($default_dir.$dir)){
			echo '<br>idUtente: '.$dir.'<br>';
			$default_dir2 = $default_dir.$dir."/";
			$dp2=opendir($default_dir2);
			while($file=readdir($dp2)){
				if(substr($file,0,5)=='file_'){
					echo '<span ';
					if(in_array($file,$files_frv)){
						echo ' style="color:#F00;"';
						unlink($default_dir2.$file);
					}
					echo '>'.$file."</span><br>";
				}
			}
		}
	}
}
?>