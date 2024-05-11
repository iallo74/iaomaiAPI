<?php
header('Content-Type: text/html; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
//header("Access-Control-Allow-Credentials: true");
//header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Authorization"); // ATTENZIONE!! NON MODIFICARE!!!!

// FUNZIONI
$method = $_SERVER['REQUEST_METHOD']; // metodo GET, POST, PUT, DELETE
if($method!='POST')die("");

if (!function_exists('getallheaders')) 
{ 
    function getallheaders() 
    { 
       $headers = array (); 
       foreach ($_SERVER as $name => $value) 
       { 
           //if (substr($name, 0, 5) == 'HTTP_') 
          // { 
               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value; 
           //} 
       } 
       return $headers; 
    } 
} 
foreach (getallheaders() as $name => $value) {
	//echo $name.": ".$value."<br>";
}


function unicode2html($str = NULL){
    $i=65535;
    while($i>0){
        $hex=dechex($i);
        $str=str_replace("\u$hex","&#$i;",$str);
        $i--;
     }
     return $str;
}
 
function conv($str = NULL){
	//return utf8_encode(html_entity_decode(unicode2html(str_replace("%u","\u",urldecode(stripslashes($str)))),ENT_QUOTES | ENT_XML1,'UTF-8'));
	return html_entity_decode(unicode2html(str_replace("%u","\u",$str)),ENT_QUOTES | ENT_HTML5,'UTF-8');
}

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
if(!$_POST["auth"])die("");
else{
	$criptata = substr($_POST["auth"],0,strlen($_POST["auth"])-6);
	$token = substr($_POST["auth"],strlen($_POST["auth"])-6,6);
	//if(decrypt($_POST["auth"],'j9.%%$hhh')!='jamil1$_HJU12')die("");	
	if(decrypt($criptata,'j9.%%$hhh')!=$token)die("");	
}



?>