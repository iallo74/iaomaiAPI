<?php
/* CANCELLARE */
header('Content-Type: text/html; charset=utf-8');
session_start();
include_once("_include/register_globals.php");

	
$list = [];
$folder = "../__files_utenti/files/";
$dp=opendir($folder);
while($file=readdir($dp)){
	if ($file[0]!='_' && $file[0]!='.' && $file[0]!='-'){
		if(is_dir($folder.$file)){
			$dp2=opendir($folder.$file."/");
			while($file2=readdir($dp2)){
				if ($file2[0]!='_' && $file2[0]!='.' && $file2[0]!='-'){
							
					if(!is_dir($folder.$file."/".$file2)){
						
						$cont_file = json_decode(file_get_contents($folder.$file."/".$file2),true);
						if($cont_file["type"]=='vid'){
							$file_name = str_replace("file_","",str_replace(".json","",$file2));
							array_push($list,$file."_".$file_name);
						}
					}
					
				}
			}
		}
	}
}
die(json_encode($list));
?>