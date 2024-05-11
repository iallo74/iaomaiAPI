<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
include_once("register_globals.php");
ob_start('ob_gzhandler');
include_once("variabili.php");
include_once("funzioni.php");
include_once("file.php");
?>