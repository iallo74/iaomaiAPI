<?php
include_once("_include/_testata.php");

$actVer = leggi_file("../../app/__actVer.txt");
$reference = "../../app/".$actVer."/js/lingue/reference_".$_GET["l"].".js";
if(!file_exists($reference))$reference = "../../app/".$actVer."/js/lingue/reference_eng.js";
if(!file_exists($reference))$reference = "../../app/".$actVer."/js/lingue/reference_ita.js";
$guida = leggi_file($reference);

$guida = str_replace("DB.reference = {","{",$guida);
$guida = str_replace("};","}",$guida);

die($guida);
?>