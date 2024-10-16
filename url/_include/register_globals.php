<?php
function register_globals($order = 'svcpge'){
    if(!function_exists('register_global_array')){
function register_global_array(array $superglobal){
            foreach($superglobal as $varname => $value){
               global $$varname;
				$$varname = $value;
				//echo $varname."=".$value."<br>";
            }
        }
    }
    $order = explode("\r\n", trim(chunk_split($order, 1)));
    foreach($order as $k){
        switch(strtolower($k)){
            case 'e':    register_global_array($_ENV);        break;
            case 'g':    register_global_array($_GET);        break;
            case 'p':    register_global_array($_POST);        break;
            case 'c':    register_global_array($_COOKIE);    break;
            case 'v':    register_global_array($_SERVER);    break;
            case 's':    register_global_array($_SESSION);    break;
        }
    }
}
function unregister_globals() {
    if (ini_get(register_globals)) {
        $array = array('_REQUEST', '_SESSION', '_SERVER', '_ENV', '_FILES');
        foreach ($array as $value) {
            foreach ($GLOBALS[$value] as $key => $var) {
                if ($var === $GLOBALS[$key]) {
                    unset($GLOBALS[$key]);
                }
            }
        }
    }
}
if(!function_exists('session_register')){ 
function session_register($var = NULL){ 
		$_SESSION[$var]=$GLOBALS[$var];
	}
}
if(!function_exists('session_is_registered')){ 
function session_is_registered($var = NULL){ 
		if($_SESSION[$var]!='')return true;
		else return false;
	}
}
//if(!get_magic_quotes_gpc()){
	$_GET    = array_map('addslashes', $_GET); 
	$_POST   = array_map('addslashes', $_POST); 
	$_COOKIE = array_map('addslashes', $_COOKIE); 
//}
if(!ini_get('register_globals'))register_globals();
?>