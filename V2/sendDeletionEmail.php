<?php
/* INALTERATO */
include_once("_include/_testata.php");
include_once('../../_include/_phpMailer/class.phpmailer.php');
include_once('../../_include/_phpMailer/class.smtp.php');
include_once('../../_include/_phpMailer/functs.php');

if($b64)$JSNPOST=urldecode(base64_decode($JSNPOST));
else $JSNPOST=stripslashes(conv($JSNPOST));
$vars=json_decode($JSNPOST);
$n=$vars->n;
$idUtente=$vars->idUtente;
$linkEl=base64_decode($vars->linkEl);
$siglaLingua=$vars->siglaLingua;
$e=$vars->e;

$EmailSito="iaomai@iaomai.app";
$NomeSito="Iáomai";	
$contenuto=file_get_contents("../../".$siglaLingua."/_moduli/iaomai/_moduloCANCELLAZIONE.htm");
$contenuto=str_replace("[NomeCliente]",FormattaHTML($n),$contenuto);
$contenuto=str_replace("[linkEl]",$linkEl,$contenuto);
$contenuto=str_replace("[idUtente]",($idUtente*24),$contenuto);
$contenuto=str_replace("[anno]",date("Y",time()),$contenuto);
$contenuto=str_replace("../../../","https://www.iaomai.app/",$contenuto);

$oggetto = Lingua([
	
		// TRADUZIONE -------------------------
		"it" => "Eliminazione account",
		"en" => "Deleting account",
		"fr" => "Suppression account",
		"es" => "Eliminación account",
		"de" => "Konto löschen",
		"pt" => "Eliminação de conta",
		"nl" => "Account verwijderen"
		
		])." ".$NomeSito;




// INVIO l'EMAIL	
mailerGo(	$e,
			$oggetto,
			$contenuto,
			'',
			$EmailSito,
			$NomeSito );

mailerGo(	"ordinicms@gmail.com",
			$oggetto,
			$contenuto,
			'',
			$EmailSito,
			$NomeSito );


die("");
?>