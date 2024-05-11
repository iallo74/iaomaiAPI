<?php
header('Content-Type: text/html; charset=utf-8');
if(!function_exists("encrypt")){
	function encrypt($string = NULL, $key = NULL) { 
		$result = ''; 
		for($i=0; $i<strlen($string); $i++) { 
			$char = substr($string, $i, 1); 
			$keychar = substr($key, ($i % strlen($key))-1, 1); 
			$char = chr(ord($char)+ord($keychar)); 
			$result.=$char; 
		}  
		return base64_encode($result); 
	} 
}
if(!function_exists("decrypt")){
	function decrypt($string = NULL, $key = NULL) { 
		$result = ''; 
		$string = base64_decode($string); 
		for($i=0; $i<strlen($string); $i++) { 
			$char = substr($string, $i, 1); 
			$keychar = substr($key, ($i % strlen($key))-1, 1); 
			$char = chr(ord($char)-ord($keychar)); 
			$result.=$char; 
		} 
		return $result; 
	}
}
if(!function_exists("unicode2html")){
	function unicode2html($str = NULL){
		$i=65535;
		while($i>0){
			$hex=dechex($i);
			$str=str_replace("\u$hex","&#$i;",$str);
			$i--;
		 }
		 return $str;
	}
}
if(!function_exists("conv")){
	function conv($str = NULL){
		//return utf8_encode(html_entity_decode(unicode2html(str_replace("%u","\u",urldecode(stripslashes($str)))),ENT_QUOTES | ENT_XML1,'UTF-8'));
		return html_entity_decode(unicode2html(str_replace("%u","\u",$str)),ENT_QUOTES | ENT_HTML5,'UTF-8');
	}
}
function generateRandomString($length = 6) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}
function jsonRemoveUnicodeSequences($struct = NULL) {
   return stripslashes(preg_replace("/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode($struct)));
}
function replace_unicode_escape_sequence($match = NULL) {
	return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
}

if(!function_exists("unicode_decode")){
	function unicode_decode($str = NULL) {
		return preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $str);
	}
}
function getDB($funct = NULL, $tabella = NULL, $query=false, $ord=false, $tipoOrd=false, $db=false){
	if(!$db)$db=$GLOBALS["RMT_db"];
	$token = generateRandomString();
	$arr = 	array(
				"db" => $db,
				"tabella" => $tabella,
				"query" => $query,
				"record" => $GLOBALS["record"],
				"record_vis" => $GLOBALS["record_vis"],
				"ordinamento" => $ord,
				"tipoOrd" => $tipoOrd
			);
	$postdata = http_build_query( 
		array(
			"JSNPOST" => base64_encode(json_encode($arr)),
			"b64" => "1",
			/*"auth" => encrypt('jamil1$_HJU12','j9.%%$hhh')*/
			"auth" => encrypt($token,'j9.%%$hhh').$token
		)
	);
	$opts = array('http' =>
		array(
			'method'  => 'POST',
			'header'  => 'Content-Type: application/x-www-form-urlencoded',
			'content' => $postdata
		),
		"ssl"=>array(
			"verify_peer"=>false,
			"verify_peer_name"=>false,
		)
	);
	$context  = stream_context_create($opts);
	$JSN = file_get_contents('https://www.corpomentespirito.it/___API/'.$funct.'.php', false, $context);
	//$JSN = utf8_encode($JSN);	
	$JSN = base64_decode($JSN);	
	/*$JSN = jsonRemoveUnicodeSequences($JSN);
	$JSN = substr($JSN,1,strlen($JSN)-2);*/
	//$JSN = stripslashes(jsonRemoveUnicodeSequences($JSN));
	//$JSN = substr($JSN,1,strlen($JSN)-2);
	//echo unicode_decode($JSN);
	//$JSN = utf8_decode(unicode_decode($JSN)); // tolto da php 7 su corpomentespirito.it
	$JSN = json_decode($JSN);
	$JSN = json_decode(json_encode($JSN), true);
	
	if(!is_array($JSN))$JSN = array();
	
	if(array_key_exists("fields", $JSN))if($JSN["fields"])$GLOBALS["fields"] = $JSN["fields"];
	
	
	if(array_key_exists("row", $JSN))if($JSN["row"])$GLOBALS["row"] = $JSN["row"];
	if(array_key_exists("row2", $JSN))if($JSN["row2"])$GLOBALS["row2"] = $JSN["row2"];
	if(array_key_exists("row3", $JSN))if($JSN["row3"])$GLOBALS["row3"] = $JSN["row3"];
	
	
	
	
	
	if(array_key_exists("record_tot", $JSN))$GLOBALS["record_tot"] = 0;
	if(array_key_exists("record_tot2", $JSN))$GLOBALS["record_tot2"] = 0;
	if(array_key_exists("record_tot3", $JSN))$GLOBALS["record_tot3"] = 0;
	if(array_key_exists("record_query", $JSN))$GLOBALS["record_query"] = 0;
	if(array_key_exists("record_query2", $JSN))$GLOBALS["record_query2"] = 0;
	if(array_key_exists("record_query3", $JSN))$GLOBALS["record_query3"] = 0;
	
	if($JSN["record_tot"])$GLOBALS["record_tot"] = $JSN["record_tot"];
	if($JSN["record_tot2"])$GLOBALS["record_tot2"] = $JSN["record_tot2"];
	if($JSN["record_tot3"])$GLOBALS["record_tot3"] = $JSN["record_tot3"];
	if($JSN["record_query"])$GLOBALS["record_query"] = $JSN["record_query"];
	if($JSN["record_query2"])$GLOBALS["record_query2"] = $JSN["record_query2"];
	if($JSN["record_query3"])$GLOBALS["record_query3"] = $JSN["record_query3"];
	return $JSN["esito"];
}

function RMT_leggi_db($tabella = NULL, $query=false, $ord=false, $tipoOrd=false, $db=false){
	return getDB( "leggi_db", $tabella, $query, $ord, $tipoOrd, $db );
}
function RMT_leggi_db2($tabella = NULL, $query=false, $ord=false, $tipoOrd=false, $db=false){
	return getDB( "leggi_db2", $tabella, $query, $ord, $tipoOrd, $db );
}
function RMT_leggi_db3($tabella = NULL, $query=false, $ord=false, $tipoOrd=false, $db=false){
	return getDB( "leggi_db3", $tabella, $query, $ord, $tipoOrd, $db );
}
function RMT_elenca_db($tabella = NULL, $query=false, $ord=false, $tipoOrd=false, $db=false){
	return getDB( "elenca_db", $tabella, $query, $ord, $tipoOrd, $db );
}
function RMT_elenca_db2($tabella = NULL, $query=false, $ord=false, $tipoOrd=false, $db=false){
	return getDB( "elenca_db2", $tabella, $query, $ord, $tipoOrd, $db );
}
function RMT_elenca_db3($tabella = NULL, $query=false, $ord=false, $tipoOrd=false, $db=false){
	return getDB( "elenca_db3", $tabella, $query, $ord, $tipoOrd, $db );
}
function RMT_Q($query = NULL, $db=false){
	return getDB( "Q", '', $query, '', '', $db );
}
function RMT_cancella($tabella = NULL, $query = NULL, $db=false){
	return getDB( "cancella", $tabella, $query, '', '', $db );
}
function RMT_aggiorna($tabella = NULL, $condizione = NULL, $db=false){
	if(getDB( "get_fields", $tabella, '', '', '', $db )){
		$query=array();
		$query["condizione"]=$condizione;
		$query["q"]=$q;
		while(list($val,$cont)=each($GLOBALS["fields"])){
			$query["q"][$cont]=addslashes($GLOBALS[$cont])."";
		}
		return getDB( "aggiorna", $tabella, json_encode($query), '', '', $db );
	}
}
function RMT_inserisci($tabella = NULL, $db=false){
	if(getDB( "get_fields", $tabella, '', '', '', $db )){
		$q=array();
		while(list($val,$cont)=each($GLOBALS["fields"])){
			$q[$cont]=addslashes($GLOBALS[$cont])."";
		}
		return getDB( "inserisci", $tabella, json_encode($q), '', '', $db );
	}
}




//$RMT_db = 'edizionicms';
//RMT_leggi_db("utenti","idUtente=1","idUtente","ASC");
//RMT_elenca_db("catalogo_clienti","idCliente","idCliente","ASC");


//print_r($row);

?> 