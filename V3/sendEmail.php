<?php
/* INALTERATO */
include_once("_include/_testata.php");
include_once('../../_include/_phpMailer/class.phpmailer.php');
include_once('../../_include/_phpMailer/class.smtp.php');
include_once('../../_include/_phpMailer/functs.php');

if($b64)$JSNPOST=urldecode(base64_decode($JSNPOST));
else $JSNPOST=stripslashes(conv($JSNPOST));
$vars=json_decode($JSNPOST);
$Email=$vars->Email;
$Oggetto=base64_decode($vars->Oggetto);
$Messaggio=base64_decode($vars->Messaggio);
$emailSender=$vars->emailSender;
$nameSender=$vars->nameSender;

if(!$emailSender)$emailSender="iaomai@iaomai.app";
if(!$nameSender)$nameSender="iáomai";	

// INVIO l'EMAIL	
mailerGo(	$Email,
			$Oggetto,
			preg_replace("/\n/","<br>",$Messaggio),
			'',
			$emailSender,
			$nameSender );


die('{"esito":"true"}');
?>