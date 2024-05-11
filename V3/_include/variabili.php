<?php

/*$format_date='D, d F Y';
$format_dateBreve='d/m/Y';*/
$format_time='H:i';
$record_vis=25;
$riga2='#888888';
$riga1='#666666';
$riga4='#eeeeee';
$riga3='#ffffff';
$ord=$GLOBALS["ord"];
$record=$GLOBALS["record"];
if ($record=='') $record=0;



					$Anno=date('Y',time());
					$Mese=date('n',time());
					$Giorno=date('j',time());
					$oggi=mktime(0,0,0,$Mese,$Giorno,$Anno);
					$domani=$oggi+86400;
					$ora=date('H',time());

$simultaneiConsentiti=10;

?>