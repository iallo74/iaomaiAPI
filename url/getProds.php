<?php
error_reporting(E_WARNING);
ini_set('display_errors', 1);
$RMT_db = 'cms2022';
define('__INCLUDE__', $_SERVER["DOCUMENT_ROOT"].'_include/');
include_once(__INCLUDE__.'funzioni.php');
include_once(__INCLUDE__.'funzioni_rmt.php');

$arr_mappe = [];
$txtTipi = array();
$txtTipi["I"] = [
		
		// TRADUZIONE -------------------------
		"it" => "Licenza illimitata multipiattaforma",
		"en" => "Unlimited cross-platform licence",
		"fr" => "Licence multiplateforme illimitée",
		"es" => "Licencia ilimitada multiplataforma",
		"de" => "Unbegrenzte plattformübergreifende Lizenz",
		"pt" => "Licença ilimitada multiplataforma",
		"nl" => "Onbeperkte cross-platform licentie"
		
		];
$txtTipi["A"] = [
		
		// TRADUZIONE -------------------------
		"it" => "Licenza annuale multipiattaforma",
		"en" => "Annual multiplatform licence",
		"fr" => "Licence annuelle multiplateforme",
		"es" => "Licencia anual multiplataforma",
		"de" => "Jährliche plattformübergreifende Lizenz",
		"pt" => "Licença anual multiplataforma",
		"nl" => "Jaarlijkse cross-platform licentie"
		
		];
$txtTipi["P"] = [
		
		// TRADUZIONE -------------------------
		"it" => "Prova 10 giorni",
		"en" => "10 days trial",
		"fr" => "Essai 10 jours",
		"es" => "Prueba 10 días",
		"de" => "10-Tage-Testversion",
		"pt" => "Teste de 10 dias",
		"nl" => "10 dagen proefperiode"
		
		];
function getProd( $idProdotto = NULL, $tipoAbb = NULL ){
	
	$pr = array();
	$pr["id_prodotto"] = $idProdotto;
	
	$pr["prezzo_listino"] = 0;
	$pr["prezzo_scontato"] = 0;
	RMT_elenca_db("(catalogo_associazioni INNER JOIN catalogo_prodotti ON catalogo_associazioni.idProdotto=catalogo_prodotti.idProdotto) INNER JOIN catalogo_dettagliprodotti ON catalogo_associazioni.idDettaglioProdotto=catalogo_dettagliprodotti.idDettaglioProdotto","catalogo_associazioni.idProdotto=".$idProdotto);
	for($k=0;$k<$GLOBALS["record_query"];$k++){
		$pr["prezzo_listino"] += $GLOBALS["row"][$k]["Prezzo"];
		$pr["id_dettaglio"] = $GLOBALS["row"][$k]["idDettaglioProdotto"];
	}
			
			
	RMT_leggi_db("catalogo_prodotti","idProdotto=".$idProdotto,'','','cms2022');
	
	$pr["titolo_it"] = $GLOBALS["row"]["NomeVisualizzato_it"];
	$pr["titolo_en"] = $GLOBALS["row"]["NomeVisualizzato_en"];
	$pr["titolo_fr"] = $GLOBALS["row"]["NomeVisualizzato_fr"];
	$pr["titolo_es"] = $GLOBALS["row"]["NomeVisualizzato_es"];
	$pr["titolo_de"] = $GLOBALS["row"]["NomeVisualizzato_de"];
	$parametri = $GLOBALS["row"]["Parametri"];
	if($parametri && substr($parametri,0,1)=='{'){
		$parametri = json_decode($parametri,true);
		$pr["folder"] = $parametri["folder"];
		$pr["id_corso"] = intval($parametri["idCorso"]);
		$pr["module"] = $parametri["module"];
		switch($parametri["tipoAbb"]){
			case "4":
				$pr["tipoAbb"] = "I";
				break;
			case "p":
				$pr["tipoAbb"] = "P";
				break;
			default:
				$pr["tipoAbb"] = "A";
				break;
		}
		if(isset($parametri["isMap"]))array_push($GLOBALS["arr_mappe"],$idProdotto);
	}
	if($tipoAbb)$pr["tipoAbb"] = $tipoAbb;
	
	$pr["specs_it"] = $GLOBALS["txtTipi"][$pr["tipoAbb"]]["it"];
	$pr["specs_en"] = $GLOBALS["txtTipi"][$pr["tipoAbb"]]["en"];
	$pr["specs_fr"] = $GLOBALS["txtTipi"][$pr["tipoAbb"]]["fr"];
	$pr["specs_es"] = $GLOBALS["txtTipi"][$pr["tipoAbb"]]["es"];
	$pr["specs_de"] = $GLOBALS["txtTipi"][$pr["tipoAbb"]]["de"];
	
	$promozione = $GLOBALS["row"]["promozione"];
	$pr["tm_fine_promo"] = $GLOBALS["row"]["DataFinePromozione"];
	if($GLOBALS["tm_fine_promo"])$pr["tm_fine_promo"] = $GLOBALS["tm_fine_promo"];
	if($pr["tm_fine_promo"])$pr["fine_promo"] = ScriviDataBreve($pr["tm_fine_promo"]);
	//echo $idProdotto." | ".$promozione." - ".$pr["tm_fine_promo"].">".$GLOBALS["oggi"].chr(10);
	if($promozione && $pr["tm_fine_promo"]>=time()/*$GLOBALS["oggi"]*/){
		$pr["prezzo_scontato"] = ($pr["prezzo_listino"]*(100-$promozione))/100;
	}
	if($pr["prezzo_scontato"]=='0' && $pr["id_corso"]!=35)$pr["prezzo_scontato"] = -1;
	if($tipoAbb=='P'){
		$pr["prezzo_listino"] = 0;
		$pr["prezzo_scontato"] = -1;
	}else{
		if(!isset($pr["prezzo_scontato"]))$pr["prezzo_scontato"] = -1;
	}
	return $pr;
}

RMT_leggi_db("variabili","idVariabile='1'","","","school");
if($row["iaomai_fineMesePromo"]=='1'){
	$tm_fine_promo = mktime(23,59,59,date('m'),date('t'),date("Y"));
	$fine_promo = ScriviDataBreve($tm_fine_promo);
}

$id = 35;
$pr[$id] = getProd( 697 );
$pr[$id]["immagine"] = 'anatomymap_scatola.jpg';
$pr[$id]["logo"] = 'logoAnatomyMapNero.png';


$id = 40;
$pr[$id] = getProd( 698 );
$pr[$id]["immagine"] = 'acupointsmap_scatola.jpg';
$pr[$id]["logo"] = 'logoAcupointsMapNero.png';

$id = 140;
$pr[$id] = getProd( 701 );
$pr[$id]["immagine"] = 'acupointsmap_scatola.jpg';
$pr[$id]["logo"] = 'logoAcupointsMapNero.png';

$id = 240;
$pr[$id] = getProd( 701, "P" );
$pr[$id]["immagine"] = 'acupointsmap_scatola.jpg';
$pr[$id]["logo"] = 'logoAcupointsMapNero.png';


$id = 37;
$pr[$id] = getProd( 699 );
$pr[$id]["immagine"] = 'shiatsumap_scatola.jpg';
$pr[$id]["logo"] = 'logoShiatsuMapNero.png';

$id = 137;
$pr[$id] = getProd( 702 );
$pr[$id]["immagine"] = 'shiatsumap_scatola.jpg';
$pr[$id]["logo"] = 'logoShiatsuMapNero.png';

$id = 237;
$pr[$id] = getProd( 702, "P" );
$pr[$id]["immagine"] = 'shiatsumap_scatola.jpg';
$pr[$id]["logo"] = 'logoShiatsuMapNero.png';





$id = 43;
$pr[$id] = getProd( 718 );
$pr[$id]["immagine"] = 'shiatsumap_scatola.jpg';
$pr[$id]["logo"] = 'logoShiatsuMapNero.png';

$id = 143;
$pr[$id] = getProd( 722 );
$pr[$id]["immagine"] = 'shiatsumap_scatola.jpg';
$pr[$id]["logo"] = 'logoShiatsuMapNero.png';

$id = 243;
$pr[$id] = getProd( 722, "P" );
$pr[$id]["immagine"] = 'shiatsumap_scatola.jpg';
$pr[$id]["logo"] = 'logoShiatsuMapNero.png';


$id = 44;
$pr[$id] = getProd( 719 );
$pr[$id]["immagine"] = 'shiatsumap_scatola.jpg';
$pr[$id]["logo"] = 'logoShiatsuMapNero.png';

$id = 144;
$pr[$id] = getProd( 723 );
$pr[$id]["immagine"] = 'shiatsumap_scatola.jpg';
$pr[$id]["logo"] = 'logoShiatsuMapNero.png';

$id = 244;
$pr[$id] = getProd( 723, "P" );
$pr[$id]["immagine"] = 'shiatsumap_scatola.jpg';
$pr[$id]["logo"] = 'logoShiatsuMapNero.png';


$id = 45;
$pr[$id] = getProd( 720 );
$pr[$id]["immagine"] = 'shiatsumap_scatola.jpg';
$pr[$id]["logo"] = 'logoShiatsuMapNero.png';

$id = 145;
$pr[$id] = getProd( 724 );
$pr[$id]["immagine"] = 'shiatsumap_scatola.jpg';
$pr[$id]["logo"] = 'logoShiatsuMapNero.png';

$id = 245;
$pr[$id] = getProd( 724, "P" );
$pr[$id]["immagine"] = 'shiatsumap_scatola.jpg';
$pr[$id]["logo"] = 'logoShiatsuMapNero.png';


$id = 47;
$pr[$id] = getProd( 721 );
$pr[$id]["immagine"] = 'shiatsumap_scatola.jpg';
$pr[$id]["logo"] = 'logoShiatsuMapNero.png';

$id = 147;
$pr[$id] = getProd( 725 );
$pr[$id]["immagine"] = 'shiatsumap_scatola.jpg';
$pr[$id]["logo"] = 'logoShiatsuMapNero.png';

$id = 247;
$pr[$id] = getProd( 725, "P" );
$pr[$id]["immagine"] = 'shiatsumap_scatola.jpg';
$pr[$id]["logo"] = 'logoShiatsuMapNero.png';








$id = 41;
$pr[$id] = getProd( 700 );
$pr[$id]["immagine"] = 'pazienti_scatola.jpg';
$pr[$id]["logo"] = 'logoSchedarioClientiNero.png';

$id = 141;
$pr[$id] = getProd( 703 );
$pr[$id]["immagine"] = 'pazienti_scatola.jpg';
$pr[$id]["logo"] = 'logoSchedarioClientiNero.png';

$id = 241;
$pr[$id] = getProd( 703, "P" );
$pr[$id]["immagine"] = 'pazienti_scatola.jpg';
$pr[$id]["logo"] = 'logoSchedarioClientiNero.png';


$id = 39;
$pr[$id] = getProd( 713 );
$pr[$id]["immagine"] = 'auriculomap_scatola.jpg';
$pr[$id]["logo"] = 'logoAuriculoMapNero.png';

$id = 139;
$pr[$id] = getProd( 714 );
$pr[$id]["immagine"] = 'auriculomap_scatola.jpg';
$pr[$id]["logo"] = 'logoAuriculoMapNero.png';

$id = 239;
$pr[$id] = getProd( 713, "P" );
$pr[$id]["immagine"] = 'auriculomap_scatola.jpg';
$pr[$id]["logo"] = 'logoAuriculoMapNero.png';


$id = 36;
$pr[$id] = getProd( 716 );
$pr[$id]["immagine"] = 'reflexologymap_scatola.jpg';
$pr[$id]["logo"] = 'logoReflexologyMapNero.png';

$id = 136;
$pr[$id] = getProd( 717 );
$pr[$id]["immagine"] = 'reflexologymap_scatola.jpg';
$pr[$id]["logo"] = 'logoReflexologyMapNero.png';

$id = 236;
$pr[$id] = getProd( 716, "P" );
$pr[$id]["immagine"] = 'reflexologymap_scatola.jpg';
$pr[$id]["logo"] = 'logoReflexologyMapNero.png';



$id = 26; // TSUBOMAP 2015
$pr[$id] = getProd( 669 );
$pr[$id]["immagine"] = 'tsubomap_scatola2015.jpg';
$pr[$id]["logo"] = 'logoTsuboMapNero.png';

// DA FARE ANCORA
/*$id = 999;
$pr[$id] = array();
$pr[$id]["folder"] = "";
$pr[$id]["titolo_it"] = 'Abbonamento FULL - Tutte le app';
$pr[$id]["specs_it"] = 'Licenza annuale multipiattaforma';
$pr[$id]["specs_en"] = 'Annual multiplatform licence';
$pr[$id]["specs_fr"] = 'Licence annuelle multiplateforme';
$pr[$id]["specs_es"] = 'Licencia anual multiplataforma';
$pr[$id]["prezzo_listino"] = 117;
$pr[$id]["prezzo_scontato"] = 99;
$pr[$id]["fine_promo"] = $fine_promo;
$pr[$id]["tm_fine_promo"] = $tm_fine_promo;
$pr[$id]["id_prodotto"] = 704;
$pr[$id]["id_dettaglio"] = 785;
$pr[$id]["id_corso"] = 999;
$pr[$id]["tipoAbb"] = "A";
$pr[$id]["immagine"] = 'full_scatola.jpg';
$pr[$id]["logo"] = 'logoFullNero.png';*/


file_put_contents("../../_include/db/prodotti.json",json_encode($pr));
file_put_contents("../../_include/db/arr_mappe.json",json_encode($arr_mappe));
?>