<?php
function FormattaData($quale_data = NULL){
	echo $GLOBALS["format_date"];
	$quale_data = date($GLOBALS["format_date"],$quale_data);
	return $quale_data;
}
function FormattaDataBreve($quale_data = NULL){
	$quale_data = date($GLOBALS["format_dateBreve"],$quale_data);
	return $quale_data;
}
function ScriviData($quale_data = NULL){
	$quale_data = strftime ($GLOBALS["format_date"],$quale_data);
	return $quale_data;
}
function ScriviDataBreve($quale_data = NULL){
	$quale_data = strftime ($GLOBALS["format_dateBreve"],$quale_data);
	return $quale_data;
}
function FormattaOra($quale_data = NULL){
	$quale_data = date($GLOBALS["format_time"],$quale_data);
	return $quale_data;
}
function Lingua($txt = NULL){
	if($txt){
		$sl = $GLOBALS["siglaLingua"];
		if(!array_key_exists ( $sl, $txt ))$sl='ita';
		return FormattaHTML($txt[$sl]);
	}
}
function LinguaTxt($txt = NULL){
	if($txt){
		$sl = $GLOBALS["siglaLingua"];
		if(!array_key_exists ( $sl, $txt ))$sl='ita';
		return $txt[$sl];
	}
}


function FormattaHTML($txt = NULL){
	$txt = htmlspecialchars($txt,ENT_COMPAT,"UTF-8");
	//$txt = htmlentities($txt,ENT_COMPAT,"iso-8859-15");
	$txt = str_replace(chr(13), '<br>', $txt);
	return $txt;
}

function errore(){
	$TXT_ATTENZIONE=LinguaTxt($GLOBALS["TXT_ATTENZIONE"]);
	if ($GLOBALS["errore"]!='') echo '<div class="errore"><b>'.$TXT_ATTENZIONE.'!!!</b><br>'.FormattaHTML($GLOBALS["errore"]).'</div>';
}
function msg(){
	if ($GLOBALS["msg"]!='') echo '<div class="errore">'.FormattaHTML($GLOBALS["msg"]).'</div>';
}


function ridimensiona($file = NULL, $W = NULL, $H = NULL, $immagine = NULL){
	$fp = ImageCreateFromJPEG($file);
	$oW=imagesx($fp);
	$oH=imagesy($fp);
	if ($oW>$W || $oH>$H) {
		$rapp=$oW/$oH;
		if ($oW>$W) {
			$nW=$W;
			$nH=$W/$rapp;
		}
		if ($oH>$H) {
			$nH=$H;
			$nW=$H*$rapp;
		}
		if ($oW>$W) {
			$nW=$W;
			$nH=$W/$rapp;
		}
		if ($oH>$H) {
			$nH=$H;
			$nW=$H*$rapp;
		}
		$fp2 =  ImageCreateTrueColor($nW,$nH);
		imagecopyresized($fp2,$fp,0,0,0,0,$nW,$nH,$oW,$oH);
		ImageJPEG($fp2, $immagine, 80 );
	}else{
		copy($file, $immagine);
	}
}
function NumeroEuro($num = NULL){
	return number_format(str_replace(",",".",str_replace(".","",ArrotondaEuro($num))),2,'.','');
}
function ArrotondaEuro($num = NULL){
	$aggiunta=0;
    $NumCar = trim($num."");
    if (substr($NumCar,0,1) == ","){
        $NumCar = '0' + $NumCar;
    }
    $punt = strpos($NumCar, ",");
    if ($punt > 0) {
      $nuovoCar = substr($NumCar, $punt+1, strlen($NumCar) - $punt);
	  $car1 = substr($nuovoCar, 0, 1);
      $car2 = substr($nuovoCar, 1, 1);
      $car3 = substr($nuovoCar, 2, 1);
	  
        if ($car2+0 == 9 && $car3+0 > 4){
            $car2+=1;
        }
        if ($car2+0 > 9){
            $car2=0;
            $car1+=1;
        }
	    if ($car1+0 > 9){
            $car1=0;
            $aggiunta=1;
        }
		$ArrotondaEuro = (substr($NumCar,0, $punt) +0+ $aggiunta).'.'.$car1.$car2;
  	}else{
		$ArrotondaEuro=$num;
	}
	return number_format($ArrotondaEuro,2,",",".");
}
function ArrotondaEuro5dec($num = NULL){
	$aggiunta=0;
    $NumCar = trim($num."");
    if (substr($NumCar,0,1) == "."){
        $NumCar = '0' + $NumCar;
    }
    $punt = strpos($NumCar, ".");
    if ($punt > 0) {
      $nuovoCar = substr($NumCar, $punt+1, strlen($NumCar) - $punt);
	  $car1 = intval(substr($nuovoCar, 0, 1));
      $car2 = intval(substr($nuovoCar, 1, 1));
      $car3 = intval(substr($nuovoCar, 2, 1));
      $car4 = intval(substr($nuovoCar, 3, 1));
      $car5 = intval(substr($nuovoCar, 4, 1));
      $car6 = intval(substr($nuovoCar, 5, 1));
        if ($car6*1 > 4){
            $car5+=1;
        }
        if ($car5*1 > 9){
            $car5=0;
            $car4+=1;
        }
        if ($car4*1 > 9){
            $car4=0;
            $car3+=1;
        }
        if ($car3*1 > 9){
            $car3=0;
            $car2+=1;
        }
        if ($car2*1 > 9){
            $car2=0;
            $car1+=1;
        }
	    if ($car1*1 > 9){
            $car1=0;
            $aggiunta=1;
        }
		$ArrotondaEuro = (substr($NumCar,0, $punt) +0+ $aggiunta).','.$car1.$car2.$car3.$car4.$car5;
  	}else{
		$ArrotondaEuro=number_format($num,2,",",".");
	}
	return $ArrotondaEuro;
}
function leggi_cartella(){
	$prrr=explode("/",$_SERVER["REQUEST_URI"]);
	return $prrr[1];
}
function utf8mail($to = NULL, $s = NULL, $body = NULL, $from_name = NULL, $from_a = NULL, $reply = NULL, $html=false){
	$type='plain';
	if($html)$type='html';
    $s= "=?utf-8?b?".base64_encode($s)."?=";
    $headers = "MIME-Version: 1.0\r\n";
    $headers.= "From: =?utf-8?b?".base64_encode($from_name)."?= <".$from_a.">\r\n";
    $headers.= "Content-Type: text/".$type.";charset=utf-8\r\n";
    $headers.= "Reply-To: $reply\r\n";  
    $headers.= "X-Mailer: PHP/" . phpversion();
    mail($to, $s, $body, $headers);
}
?>