<?php
include_once("_include/_testata.php");
include_once('../../_include/_phpMailer/class.phpmailer.php');
include_once('../../_include/_phpMailer/class.smtp.php');
include_once('../../_include/_phpMailer/functs.php');

if($b64)$JSNPOST=urldecode(base64_decode($JSNPOST));
else $JSNPOST=stripslashes(conv($JSNPOST));
$vars=json_decode($JSNPOST);
$n=$vars->n;
$r=$vars->r;
$Dettagli=$vars->Dettagli;
$pagamento=$vars->pagamento;
$siglaLingua=$vars->siglaLingua;
$o=$vars->o;
$d=$vars->d;
$e=$vars->e;


$Prodotto=Lingua([
	
		// TRADUZIONE -------------------------
		"it" => "Abbonamento ILLIMITATO a Iáomai",
		"en" => "UNLIMITED subscription to Iáomai",
		"fr" => "Abonnement ILLIMITÉ à Iáomai",
		"es" => "Suscripción ILIMITADA a Iáomai",
		"de" => "",
		"pt" => "",
		"nl" => ""
		
		]);


$EmailSito="iaomai@iaomai.app";
$NomeSito="Iáomai";	
$contenuto=leggi_file("../../".$siglaLingua."/_moduli/iaomai/_moduloORDINI.htm");
$contenuto=str_replace("[NomeCliente]",FormattaHTML($n),$contenuto);
$contenuto=str_replace("[Prezzo]",ArrotondaEuro($r),$contenuto);
$contenuto=str_replace("[Prodotto]",FormattaHTML($Prodotto),$contenuto);
$contenuto=str_replace("[Dettagli]",$Dettagli,$contenuto);
$contenuto=str_replace("[pagamento]",$pagamento,$contenuto);
$contenuto=str_replace("[Data]",$d,$contenuto);
$contenuto=str_replace("[anno]",date("Y",time()),$contenuto);
$contenuto=str_replace("../../../","https://www.iaomai.app/",$contenuto);

$oggetto = Lingua([
	
		// TRADUZIONE -------------------------
		"it" => "Ordine su",
		"en" => "Your order on",
		"fr" => "Commande sur",
		"es" => "Pedido en",
		"de" => "",
		"pt" => "",
		"nl" => ""
		
		])." ".$NomeSito." (".$o.")";




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