<?php
if (!function_exists('getallheaders')){ 
    function getallheaders(){ 
       $headers = array (); 
       foreach ($_SERVER as $name => $value){ 
          $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value; 
       } 
       return $headers; 
    } 
}
?>