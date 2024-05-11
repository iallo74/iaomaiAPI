<?php 
function leggi_file($nome_file = NULL)
{
	$fp = @ fopen($nome_file, 'r');
	if (!$fp) return;
	$len=@filesize($nome_file);
	$contenuto_file=fread($fp, $len);
	flock($fp, LOCK_UN);
	fclose($fp);
	return $contenuto_file;
}
function salva_file($nome_file = NULL, $contenuto_file = NULL)
{
	$fp = @ fopen($nome_file, 'w');
	flock($fp, LOCK_EX);
	if (!fwrite($fp,$contenuto_file)) $GLOBALS["errore"]='impossibile creare o modificare un file';
	fclose($fp);
}
?>