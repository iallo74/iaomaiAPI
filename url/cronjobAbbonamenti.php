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

$TipiAbb = [
	"" => "Annuale",
	"b" => "60 giorni",
	"m" => "30 giorni",
	"p" => "10 giorni"
];
$GiorniAvviso1 = [
	"" => 30,
	"b" => 7,
	"m" => 7,
	"p" => 3
];
$GiorniAvviso2 = [
	"" => 7,
	"b" => -1,
	"m" => -1,
	"p" => -1
];
$GiorniAvvisoDopo = [
	"" => 30,
	"b" => 15,
	"m" => 7,
	"p" => 7
];
$loghi = [
	37 => "../../../_img/iaomai/logoShiatsuMapNero.png",
	39 => "../../../_img/iaomai/logoAuricologyMapNero.png",
	41 => "../../../_img/iaomai/logoSchedarioClientiNero.png",
	40 => "../../../_img/iaomai/logoTsuboMapNero.png",
	35 => "../../../_img/iaomai/logoAnatomyMapNero.png",
	36 => "../../../_img/iaomai/logoReflexologyyMapNero.png"
];
$oggetti = [
	"PRE" => [

	// TRADUZIONE -------------------------
	"it" => 'Rinnova il tuo abbonamento in scadenza',
	"en" => 'Renew your expiring subscription',
	"fr" => 'Renouvelez votre abonnement à expiration',
	"es" => 'Renueve su suscripción que caduca',
	"de" => '',
	"pt" => '',
	"nl" => ''
	
	],
	"OGGI" => [

	// TRADUZIONE -------------------------
	"it" => 'Abbonamento SCADUTO',
	"en" => 'Subscription expired',
	"fr" => 'Abonnement a expiré',
	"es" => 'Suscripción expirada',
	"de" => '',
	"pt" => '',
	"nl" => ''
	
	],
	"DOPO" => [

	// TRADUZIONE -------------------------
	"it" => 'Rinnovo abbonamento, ultimo avviso',
	"en" => 'Renewal of subscription, last notice',
	"fr" => 'Renouvellement d\'abonnement, dernier avis',
	"es" => 'Renovación de suscripción, último aviso',
	"de" => '',
	"pt" => '',
	"nl" => ''
	
	]
];
$logoBase = '<img src="[img]" style="width:30px;height:30px;margin-right:10px;vertical-align:middle;">';
$adesso = time();
//$adesso = mktime(5,0,0,3,19,2023);
$oggi = mktime(0,0,0,date("m",$adesso),date("d",$adesso),date("Y",$adesso));

function maxGiorniDopo(){
	$val = 0;
	foreach($GLOBALS["GiorniAvvisoDopo"] as $g){
		if($g>$val)$val = $g;
	}
	return $val;
}
function maxGiorniPrima(){
	$val = 0;
	foreach($GLOBALS["GiorniAvviso1"] as $g){
		if($g>$val)$val = $g;
	}
	return $val;
}


//?acc=ert_Gert$
$utenti = array();
$record_vis=100000;

RMT_elenca_db(	"(abbonamenti INNER JOIN corsi ON abbonamenti.idCorso=corsi.idCorso) INNER JOIN utenti ON abbonamenti.idUtente=utenti.idUtente",
				"abbonamenti.Attivo='1' AND ".
				"corsi.idCorso<>'35' AND ". // escludo AnatomyMap
				"corsi.idCorso<>'2' AND ". // escludo vecchio Punti del riequilibrio
				"corsi.tipoCorso='cdrom' AND ".
				"(	abbonamenti.tipoAbbStream='' OR ".	// annuale
				//"	abbonamenti.tipoAbbStream='p' OR ".	// prova 10 gg
				"	abbonamenti.tipoAbbStream='m' OR ". // 30 gg
				"	abbonamenti.tipoAbbStream='b' )",	// 60 gg
				"abbonamenti.DataFine",
				"ASC",
				"school" );


if ($record_query!=0){
	for ($n=0; $n<$record_query; $n++){
		$pass = true;
		/*RMT_leggi_db2("abbonamenti","Attivo='1' AND idUtente=".$row[$n]["idUtente"]." AND idAbbonamento<>".$row[$n]["idAbbonamento"]." AND idCorso=".$row[$n]["idCorso"]." AND DataFine>".$row[$n]["DataFine"],"","","school");
		if($record_tot2>0)$pass = false;*/
		
		$DataFine = mktime(0,0,0,date("m",$row[$n]["DataFine"]),date("d",$row[$n]["DataFine"]),date("Y",$row[$n]["DataFine"]));
		if($DataFine>0 && $pass){
			$key = $row[$n]["idUtente"]."|".$DataFine;
			
			if(!$utenti[$key]){
				$utenti[$key] = array();
				$utenti[$key]["idUtente"] = $row[$n]["idUtente"]*123;
				$utenti[$key]["Nominativo"] = $row[$n]["Nominativo"];
				$utenti[$key]["Email"] = $row[$n]["Email"];
				$utenti[$key]["idCliente"] = $row[$n]["idClienteCMS"];
				$sigla2 = 'it';
				if($row[$n]["idLingua"]){
					RMT_leggi_db2("lingue","idLingua=".$row[$n]["idLingua"],"","","school");
					$sigla2 = $row2["sigla2"];
				}
				$utenti[$key]["sigla2"] = $sigla2;
				
				$utenti[$key]["codice"] = md5($row[$n]["UsernameU"]."45$frt".$row[$n]["PasswordU"]);
				$utenti[$key]["corsi"] = array();
			}
			$utenti[$key]["corsi"][$n] = array();
			$utenti[$key]["corsi"][$n]["id"] = $row[$n]["idCorso"];
			$utenti[$key]["corsi"][$n]["idAbbonamento"] = $row[$n]["idAbbonamento"];
			$utenti[$key]["corsi"][$n]["titolo"] = $row[$n]["TitoloCorso_ita"];
			$utenti[$key]["corsi"][$n]["tipoAbbStream"] = $row[$n]["tipoAbbStream"];
			$utenti[$key]["corsi"][$n]["AvvertitoPre1"] = $row[$n]["AvvertitoPre1"];
			$utenti[$key]["corsi"][$n]["AvvertitoPre2"] = $row[$n]["AvvertitoPre2"];
			$utenti[$key]["corsi"][$n]["AvvertitoScadenza"] = $row[$n]["AvvertitoScadenza"];
			$utenti[$key]["corsi"][$n]["AvvertitoDopo"] = $row[$n]["AvvertitoDopo"];
		}
	}
	
}


function avvisaEmail( $codice, $idUtente, $Nominativo, $Email, $DF, $sigla2,  $corsi, $tipo, $idCliente){
	if($GLOBALS["debug"])echo '<font color="#FF0000">Invia email con avviso scadenza <b>'.$tipo."</b> a ".$Email."</font><br>";
	$addURL = 'PRE';
	if($tipo == 'S')$addURL = 'OGGI';
	if($tipo == 'D')$addURL = 'DOPO';
	$url = '../../'.$sigla2.'/_moduli/iaomai/_moduloRINNOVO_'.$addURL.'.htm';
	
	$contenuto = leggi_file($url);
	
	$contenuto = str_replace("[NomeCliente]",$Nominativo,$contenuto);
	$contenuto = str_replace("[DataFine]",FormattaDataBreve($DF),$contenuto);
	$contenuto = str_replace("[codice]",$codice,$contenuto);
	$contenuto = str_replace("[idUtente]",$idUtente,$contenuto);
	$contenuto = str_replace("[anno]",date("Y",time()),$contenuto);
	
	$Applicazioni = '';
	foreach($corsi as $corso){
		$pC = explode("§",$corso);
		$idAbbonamento = $pC[0];
		$NomeApp = $pC[1];
		$idCorso = $pC[2];
		$pass = true;
		RMT_leggi_db2("abbonamenti","Attivo='1' AND idUtente=".($idUtente/123)." AND idAbbonamento<>".$idAbbonamento." AND idCorso=".$idCorso." AND DataFine>".$DF,"","","school");
		if($GLOBALS["record_tot2"]>0)$pass = false;
		if($pass){
			$Applicazioni .= $NomeApp.'<br>';
			
			// setta inviato
			$campoAvvertito = 'Avvertito';
			if($tipo=='1')$campoAvvertito .= 'Pre1';
			if($tipo=='2')$campoAvvertito .= 'Pre2';
			if($tipo=='S')$campoAvvertito .= 'Scadenza';
			if($tipo=='D')$campoAvvertito .= 'Dopo';
			if(!$GLOBALS["debug"])RMT_Q("UPDATE abbonamenti SET ".$campoAvvertito."='".time()."' WHERE idAbbonamento='".$idAbbonamento."'","school");
		}
	}
	$contenuto = str_replace("[Applicazioni]",$Applicazioni,$contenuto);
	$contenuto = str_replace("../../../","https://www.iaomai.app/",$contenuto);
	if($Applicazioni){
		if($GLOBALS["debug"])echo $contenuto;
		else{
			// invia email
			mailerGo(	$Email,
						$GLOBALS["oggetti"][$addURL][$sigla2],
						$contenuto,
						'',
						"iaomai@iaomai.app",
						'Iáomai' );
			$Oggetto = $GLOBALS["oggetti"][$addURL][$sigla2];
			$Messaggio = $contenuto;
			$DataInvio = time();			
			RMT_inserisci("email_logs","cms2022");
				
		}
	}
}
/*

Avvisiamo 2 volte prima della scadenza
Avvisiamo il giorno della scadenza
Avvisiamo 30 giorni dopo la scadenza

*/
while(list($key,$ut)=each($utenti)){
	
	
	$RIGA = '';
	$passUt = false;
	
	$pU = explode("|",$key);
	$id = intval($pU[0]);
	$DF = intval($pU[1]);
	
	$RIGA .= "<b>".$ut["Nominativo"]." (".$ut["sigla2"].")</b><br>";
	
	$corsiAvviso1 = array();
	$corsiAvviso2 = array();
	$corsiAvvisoScadenza = array();
	$corsiAvvisoDopo = array();
	
	
	foreach($ut["corsi"] as $corso){
		$TA = $corso["tipoAbbStream"];
		$A1 = $corso["AvvertitoPre1"];
		$A2 = $corso["AvvertitoPre2"];
		$AS = $corso["AvvertitoScadenza"];
		$AD = $corso["AvvertitoDopo"];
		
		$dataScadenza = mktime(0,0,0,date("m",$DF),date("d",$DF),date("Y",$DF));
		
		$firstDate  = new DateTime(date("Y",$oggi)."-".date("m",$oggi)."-".date("d",$oggi));
		$secondDate = new DateTime(date("Y",$DF)."-".date("m",$DF)."-".date("d",$DF));
		$intvl = $firstDate->diff($secondDate);
		$diff = $intvl->days;
		
		$pass = true;
		
		if($oggi<$dataScadenza){
			if($diff>maxGiorniPrima())$pass = false;
			$diff = 0-$diff;
		}else{
			if($diff>maxGiorniDopo())$pass = false;
		}
		
		
		if($pass){
			$passUt = true;
			$RIGA .= "- ".$corso["titolo"]." (".ScriviDataBreve($DF,$ut["sigla2"]).") <b>[".$diff."]</b> ".$TipiAbb[$TA]."<br>";
			$titolo = $corso["idAbbonamento"]."§".str_replace("[img]",$loghi[$corso["id"]],$logoBase).$corso["titolo"]."§".$corso["id"];
			// avviso 1
			$dataPre1 = mktime(0,0,0,date("m",$DF-$GiorniPrima1),date("d",$DF-$GiorniPrima1),date("Y",$DF-$GiorniPrima1));
			if(0-$GiorniAvviso1[$TA]==$diff && !$A1){
				array_push($corsiAvviso1,$titolo);
			}
			
			// avviso 2 (solo annuale)
			if($GiorniAvviso2[$TA]>-1){
				$dataPre2 = mktime(0,0,0,date("m",$DF-$GiorniPrima2),date("d",$DF-$GiorniPrima2),date("Y",$DF-$GiorniPrima2));
				if(0-$GiorniAvviso2[$TA]==$diff && !$A2){
					array_push($corsiAvviso2,$titolo);
				}
			}
			
			// avviso alla scadenza
			if($diff==0 && !$AS){
				array_push($corsiAvvisoScadenza,$titolo);
			}
			
			// avviso dopo
			$dataDopo = mktime(0,0,0,date("m",$DF+$GiorniDopo),date("d",$DF+$GiorniDopo),date("Y",$DF+$GiorniDopo));
			if($GiorniAvvisoDopo[$TA]==$diff && !$AD){
				array_push($corsiAvvisoDopo,$titolo);
			}
		
		}
	}
	
	if($passUt){
		if($debug)echo $RIGA;
		if(count($corsiAvviso1)>0)avvisaEmail( $ut["codice"], $ut["idUtente"], $ut["Nominativo"], $ut["Email"], $DF, $ut["sigla2"], $corsiAvviso1, '1', $ut["idCliente"] ); 
		if(count($corsiAvviso2)>0)avvisaEmail( $ut["codice"], $ut["idUtente"], $ut["Nominativo"], $ut["Email"], $DF, $ut["sigla2"], $corsiAvviso2, '2', $ut["idCliente"] ); 
		if(count($corsiAvvisoScadenza)>0)avvisaEmail( $ut["codice"], $ut["idUtente"], $ut["Nominativo"], $ut["Email"], $DF, $ut["sigla2"], $corsiAvvisoScadenza, 'S', $ut["idCliente"] ); 
		if(count($corsiAvvisoDopo)>0)avvisaEmail( $ut["codice"], $ut["idUtente"], $ut["Nominativo"], $ut["Email"], $DF, $ut["sigla2"], $corsiAvvisoDopo, 'D', $ut["idCliente"] ); 
		if($debug)echo "<br><br>";
	}
}
?>
