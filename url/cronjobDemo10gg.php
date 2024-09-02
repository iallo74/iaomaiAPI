<?php
include_once('_include/_testata.php');
include_once("../../_include/funzioni_rmt.php");
include_once('../../_include/_phpMailer/class.phpmailer.php');
include_once('../../_include/_phpMailer/class.smtp.php');
include_once('../../_include/_phpMailer/functs.php');

if($_GET["acc"]!="ert_Gert$"){
	die("Accesso negato");
}
//$debug = true;

$oggi = date("Y",time())."-".date("m",time())."-".date("d",time());
$g5 = array();
$g9 = array();
$record_vis=100000;

// elenco tutti gli abbonamenti a 10GG non ancora scaduti
RMT_elenca_db(	"((abbonamenti INNER JOIN corsi ON abbonamenti.idCorso=corsi.idCorso) INNER JOIN utenti ON abbonamenti.idUtente=utenti.idUtente) LEFT JOIN lingue ON utenti.idLingua=lingue.idLingua",
				"abbonamenti.Attivo='1' AND ".
				"corsi.idCorso<>'35' AND ". // escludo AnatomyMap
				"corsi.idCorso<>'2' AND ". // escludo vecchio Punti del riequilibrio
				"corsi.tipoCorso='cdrom' AND ".
				"abbonamenti.tipoAbbStream='p' AND ".
				"abbonamenti.DataFine>".time(),
				"abbonamenti.DataFine",
				"ASC",
				"school" );
if ($record_query!=0){
	for ($n=0; $n<$record_query; $n++){
		$DataFine = date("Y",$row[$n]["DataFine"])."-".date("m",$row[$n]["DataFine"])."-".date("d",$row[$n]["DataFine"]);
		$origin = new DateTimeImmutable($DataFine);
		$target = new DateTimeImmutable($oggi);
		$interval = $origin->diff($target);
		$diff = $interval->format('%a');
		$dati = [
			"NomeCliente" => $row[$n]["Nominativo"],
			"idCliente" => $row[$n]["idClienteCMS"],
			"Email" => $row[$n]["Email"],
			"sigla2" => $row[$n]["sigla2"]
		];
		if($diff==5){
			if(!in_array($g5[$row[$n]["idUtente"]]))$g5[$row[$n]["idUtente"]] = $dati;
		}
		if($diff==1){
			if(!in_array($g9[$row[$n]["idUtente"]]))$g9[$row[$n]["idUtente"]] = $dati;
		}
	}
}
// leggo i modelli
$cont_g5_it = leggi_file('../../it/_moduli/iaomai/_moduloDEMO10gg_g5.htm');
$cont_g5_en = leggi_file('../../en/_moduli/iaomai/_moduloDEMO10gg_g5.htm');
$cont_g5_es = leggi_file('../../es/_moduli/iaomai/_moduloDEMO10gg_g5.htm');
$cont_g5_fr = leggi_file('../../fr/_moduli/iaomai/_moduloDEMO10gg_g5.htm');
$cont_g5_de = leggi_file('../../de/_moduli/iaomai/_moduloDEMO10gg_g5.htm');
$cont_g9_it = leggi_file('../../it/_moduli/iaomai/_moduloDEMO10gg_g9.htm');
$cont_g9_en = leggi_file('../../en/_moduli/iaomai/_moduloDEMO10gg_g9.htm');
$cont_g9_es = leggi_file('../../es/_moduli/iaomai/_moduloDEMO10gg_g9.htm');
$cont_g9_fr = leggi_file('../../fr/_moduli/iaomai/_moduloDEMO10gg_g9.htm');
$cont_g9_de = leggi_file('../../de/_moduli/iaomai/_moduloDEMO10gg_g9.htm');

$ogg_g5 = [

	// TRADUZIONE -------------------------
	"it" => 'Come procede la tua prova di 10 giorni?',
	"en" => 'How is your 10-day trial going?',
	"fr" => 'Comment se passe votre essai de 10 jours ?',
	"es" => '¿Cómo va tu prueba de 10 días?',
	"de" => 'Wie läuft deine 10-tägige Testphase?',
	"pt" => 'Como está o seu teste de 10 dias?',
	"nl" => 'Hoe verloopt je 10-daagse proef?'
	
	];

$ogg_g9 = [

	// TRADUZIONE -------------------------
	"it" => 'Prova 10 giorni in scadenza',
	"en" => '10-day trial expiring',
	"fr" => 'Essai de 10 jours expirant',
	"es" => 'Prueba de 10 días que expira',
	"de" => '10-tägige Testphase läuft ab',
	"pt" => 'Teste de 10 dias expirando',
	"nl" => '10-daagse proef vervalt'
	
	];

foreach($g5 as $idUtente=>$dati){
	
	if($dati["sigla2"])$sigla2 = $dati["sigla2"];
	else $sigla2 = 'it';
	$Messaggio = $GLOBALS["cont_g5_".$sigla2];
	$Messaggio = str_replace("[NomeCliente]",$dati["NomeCliente"],$Messaggio);
	$Messaggio = str_replace("[anno]",date("Y",time()),$Messaggio);
	$Messaggio = str_replace("../../../","https://www.iaomai.app/",$Messaggio);
	$Messaggio = str_replace("../../","https://www.iaomai.app/".$sigla2."/",$Messaggio);
	$Email = $dati["Email"];
	$Oggetto = $ogg_g5[$sigla2];
	$idCliente = $dati["idCliente"];
	$DataInvio = time();

	//echo $contenuto;
	mailerGo(	$Email,
				$Oggetto,
				$Messaggio,
				'',
				"iaomai@iaomai.app",
				'iáomai' );
	/*mailerGo(	"c.iallo@hotmail.it",
				$Oggetto,
				$Messaggio,
				'',
				"iaomai@iaomai.app",
				'iáomai' );*/
	RMT_inserisci("email_logs","cms2022");
}
//echo "<hr>";
foreach($g9 as $idUtente=>$dati){
	
	
	if($dati["sigla2"])$sigla2 = $dati["sigla2"];
	else $sigla2 = 'it';
	$Messaggio = $GLOBALS["cont_g9_".$sigla2];
	$Messaggio = str_replace("[NomeCliente]",$dati["NomeCliente"],$Messaggio);
	$Messaggio = str_replace("[anno]",date("Y",time()),$Messaggio);
	$Messaggio = str_replace("../../../","https://www.iaomai.app/",$Messaggio);
	$Messaggio = str_replace("../../","https://www.iaomai.app/".$sigla2."/",$Messaggio);
	$Email = $dati["Email"];
	$Oggetto = $ogg_g9[$sigla2];
	$idCliente = $dati["idCliente"];
	$DataInvio = time();

	//echo $contenuto;
	mailerGo(	$Email,
				$Oggetto,
				$Messaggio,
				'',
				"iaomai@iaomai.app",
				'iáomai' );
	/*mailerGo(	"c.iallo@hotmail.it",
				$Oggetto,
				$Messaggio,
				'',
				"iaomai@iaomai.app",
				'iáomai' );*/
	RMT_inserisci("email_logs","cms2022");
}
?>