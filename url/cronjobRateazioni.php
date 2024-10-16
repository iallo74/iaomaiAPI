<?php
include_once('_include/_testata.php');
include_once("../../_include/funzioni_rmt.php");
include_once('../../_include/_phpMailer/class.phpmailer.php');
include_once('../../_include/_phpMailer/class.smtp.php');
include_once('../../_include/_phpMailer/functs.php');

function setLocal(){
	// ----------ITALIANO-SPAGNOLO-FRANCESE---------------//
	if($GLOBALS["lang"]=='it' || $GLOBALS["lang"]=='es' || $GLOBALS["lang"]=='fr'){
		setlocale(LC_TIME, 'ita', 'it_IT');
		$GLOBALS["format_date"]='%a, %d %B %Y';
		$GLOBALS["format_dateBreve"]='%d/%m/%Y';
	}
	// --------------------ENGLISH------------------------//
	if($GLOBALS["lang"]=='en'){
		setlocale(LC_TIME, 'eng', 'en_US');
		$GLOBALS["format_date"]='%b %d, %Y';
		$GLOBALS["format_dateBreve"]='%m/%d/%Y';
	}
	// --------------------ARABO------------------------//
	if($GLOBALS["lang"]=='ar'){
		setlocale(LC_TIME, 'ara', 'ar_AR');
		$GLOBALS["format_date"]='%a, %d %B %Y';
		$GLOBALS["format_dateBreve"]='%d/%m/%Y';
	}
}

if($_GET["acc"]!="ert_Gert$"){
	die("Accesso negato");
}

$ordini = array();
$record_vis=100000;
//$debug = true;



$date = [
	"1" => [
		"data" => time()+(60*60*24*5),
		"titolo" => [

			// TRADUZIONE -------------------------
			"it" => 'Prossima scadenza rata',
			"en" => '',
			"fr" => '',
			"es" => '',
			"de" => '',
			"pt" => '',
			"nl" => ''
		
		],
		"msg" => [
		
			// TRADUZIONE -------------------------
			"it" => 'ti ricordiamo che una delle rate relative alla tua licenza sta per scadere. Paga entro il giorno [day] per evitarne la sospensione',
			"en" => '',
			"fr" => '',
			"es" => '',
			"de" => '',
			"pt" => '',
			"nl" => ''
		
		]
	],
	"2" => [
		"data" => time(),
		"titolo" => [

			// TRADUZIONE -------------------------
			"it" => 'Avviso rata scaduta',
			"en" => '',
			"fr" => '',
			"es" => '',
			"de" => '',
			"pt" => '',
			"nl" => ''
		
		],
		"msg" => [
		
			// TRADUZIONE -------------------------
			"it" => 'una delle rate relative alla tua licenza risulta scaduta il giorno [day]',
			"en" => '',
			"fr" => '',
			"es" => '',
			"de" => '',
			"pt" => '',
			"nl" => ''
		
		]
	]
];

$lingue = json_decode(file_get_contents("../../_include/db/lingue.json"),true);

foreach($date as $TP => $EL){
	// elenco tutte le rateazioni scadure
	RMT_elenca_db(	"catalogo_rate INNER JOIN catalogo_ordini ON catalogo_rate.idOrdine=catalogo_ordini.idOrdine",
					"(catalogo_rate.DataRata1<".$EL["data"]." AND catalogo_rate.DataPagamento1=0) OR ".
					"(catalogo_rate.DataRata2<".$EL["data"]." AND catalogo_rate.DataPagamento2=0) OR ".
					"(catalogo_rate.DataRata3<".$EL["data"]." AND catalogo_rate.DataPagamento3=0)",
					"catalogo_rate.idOrdine",
					"ASC",
					"cms2022" );
					
	if ($record_query!=0){
		for ($n=0; $n<$record_query; $n++){
			if(!$ordini[$row[$n]["idOrdine"]]){
				$sigla2 = 'it';
				if($row[$n]["idLingua"]){
					$sigla2 = $lingue[$row[$n]["idLingua"]]["sigla2"];
				}
				$ImportoRata = 0;
				if($row[$n]["DataRata3"]<$EL["data"]){
					
					if(intval($row[$n]["Avvisato3"])<intval($TP)){
					
						$ImportoRata = $row[$n]["ValoreRata3"];
						$DataScadenza = $row[$n]["DataRata3"];
						$nRata = 3;
						$addP = '&r3='.$ImportoRata;
						
					}
				}
				if($row[$n]["DataRata2"]<$EL["data"]){
					
					if(intval($row[$n]["Avvisato2"])<intval($TP)){
					
						$ImportoRata = $row[$n]["ValoreRata2"];
						$DataScadenza = $row[$n]["DataRata2"];
						$nRata = 2;
						$addP = '&r2='.$ImportoRata;
					
					}
				}
				
				if($ImportoRata){
					$jsn = '{"o":"'.$row[$n]["idOrdine"].'","i":"'.$row[$n]["idCliente"].'","c":"'.$row[$n]["Casuale"].'"}';
					$o = intval($row[$n]["idOrdine"])+27;
					$i = intval($row[$n]["idCliente"])*2537;
					$str=	'k='.md5(urlencode(base64_encode($jsn)))."&" .
							'o='.$o."&" .
							'i='.$i.$addP;
					$linkPagamento="https://www.iaomai.app/_mypos/payment_link.php?".$str;
					$lang = $sigla2;
					$titolo = $EL["titolo"][$lang];
					setLocal();
					$msg = str_replace("[day]",strftime ($format_dateBreve,$DataScadenza),$EL["msg"][$lang]);
					$url = '../../'.$sigla2.'/_moduli/iaomai/_moduloRATEAZIONE.htm';
					$contenuto = file_get_contents($url);
					$contenuto = str_replace("[Titolo]",$titolo,$contenuto);
					$contenuto = str_replace("[msg]",$msg,$contenuto);
					$contenuto = str_replace("[NomeCliente]",$row[$n]["NomeCliente"],$contenuto);
					$contenuto = str_replace("[ImportoRata]","€ ".ArrotondaEuro($ImportoRata),$contenuto);
					$contenuto = str_replace("[linkPagamento]",$linkPagamento,$contenuto);
					$contenuto = str_replace("[nRata]",$nRata,$contenuto);
					$contenuto = str_replace("[order_id]",$row[$n]["idOrdine"]."-".$row[$n]["Casuale"],$contenuto);
					$contenuto = str_replace("[anno]",date("Y",time()),$contenuto);
					$contenuto = str_replace("../../../","https://www.iaomai.app/",$contenuto);
		
					if($debug)echo $contenuto;
					else{
						// invia email
						mailerGo(	$row[$n]["Email"],
									$titolo,
									$contenuto,
									'',
									"iaomai@iaomai.app",
									'Iáomai' );
						mailerGo(	"ordinicms@gmail.com",
									$titolo,
									$contenuto,
									'',
									"iaomai@iaomai.app",
									'Iáomai' );
									
						RMT_Q("UPDATE catalogo_rate SET Avvisato".$nRata."='".$TP."' WHERE idRata=".$row[$n]["idRata"],"cms2022");
							
					}
				}
			}
		}
	}
}

?>
