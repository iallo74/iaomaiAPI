<?php
function cambia_riga(){
	if ($GLOBALS["bg_riga"]==$GLOBALS["riga1"]) {
		$GLOBALS["bg_riga"]=$GLOBALS["riga2"];
	}else{
		$GLOBALS["bg_riga"]=$GLOBALS["riga1"];
	}
}
function cambia_riga2(){
	if ($GLOBALS["bg_riga2"]==$GLOBALS["riga3"]) {
		$GLOBALS["bg_riga2"]=$GLOBALS["riga4"];
	}else{
		$GLOBALS["bg_riga2"]=$GLOBALS["riga3"];
	}
}
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

function tag_orari($txt = NULL){
	$txt = str_replace('[#', '<span class="menutesta">', $txt);
	$txt = str_replace('#]', '</span>', $txt);
	return $txt;
}

function tag_bold($txt = NULL){
	$txt = str_replace('[#', '<b>', $txt);
	$txt = str_replace('#]', '</b>', $txt);
	return $txt;
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
function DisegnaSelect($nome = NULL, $class = NULL, $elenco = NULL, $selezione = NULL, $size = NULL, $on=false, $controllo=false){
	if(!$controllo)$controllo=$nome;
	echo '<select name="'.$nome.'" id="'.$controllo.'"';
	if ($size) echo ' size="'.$size.'"';
	if ($class) echo ' class="'.$class.'"';
	if ($on) echo $on;
	echo '>';
	while (list($valore,$contenuto) = each($elenco)){
		echo '<option value="'.$valore.'"';
		if ($valore==$selezione) echo 'SELECTED';
		echo '>'.$contenuto.'</option>
		';
	}
	echo '</select>';
}
function DisegnaPaginazione($qqq = NULL){ 
	if ($qqq) $qqq="&".$qqq;
	$pagine_tot=ceil($GLOBALS["record_tot"]/$GLOBALS["record_vis"]);
	$pagina_attuale=ceil($GLOBALS["record"]/$GLOBALS["record_vis"]);
	$pagine_vis=100;
	if ($pagine_tot>1){
		echo '<span class="labels">'.$GLOBALS["testoPagine"].':</span> ';
		$conteggio_pagine=0;
		for ($k=0; $k<$pagine_tot; $k++){
			$conteggio_pagine++;
			if ((($k>=$pagina_attuale-$pagine_vis) && ($k<=$pagina_attuale+$pagine_vis)) && (($k<($pagine_vis*2)+1) || ($k>$pagine_tot-(($pagine_vis*2)+1)))) {
				if ($pagina_attuale!=$k) echo '<a href="'.$PHP_SELF.'?record='.($GLOBALS["record_vis"]*$k).$qqq.'">';
				else echo '<span class="separators">';
				echo $k+1;
				if ($pagina_attuale!=$k) echo '</a>';
				else echo '</span>';
				echo ' ';
			}
		}
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


/* DB */
function TotaleRecord($query = NULL){
	$result = @ mysqli_query ($GLOBALS["Fconnection"],$query);
	return @ mysqli_num_rows($result);
}
function mysqli_field_name($result = NULL, $field_offset = NULL){
    $properties = mysqli_fetch_field_direct($result, $field_offset);
    return is_object($properties) ? $properties->name : null;
}
function mod_db($nome_tabella = NULL, $condizione=''){
	$query = "SELECT * FROM ".$nome_tabella;		
	$result = @ mysqli_query ($GLOBALS["Fconnection"],$query);
	$query = "LOCK TABLES ".$nome_tabella." WRITE";
	@ mysqli_query ($GLOBALS["Fconnection"],$query);	
	if ($condizione=='') $query="INSERT INTO ".$nome_tabella." VALUES (null, ";
	else $query="UPDATE ".$nome_tabella." SET ";
	for ($n=1; $n<mysqli_num_fields($result); $n++){
		if ($condizione) $query.=mysqli_field_name($result,$n)."=";
		$query.="'".$GLOBALS[mysqli_field_name($result,$n)]."'";
		if ($n<mysqli_num_fields($result)-1) $query.=", ";
	}
	if ($condizione=='') $query.=")";
	else $query.=" WHERE ".$condizione;
	if (!($result = @ mysqli_query ($GLOBALS["Fconnection"], $query))) $GLOBALS["errore"]='si è verificato un errore';
	$query = "UNLOCK TABLES";
	@ mysqli_query ($GLOBALS["Fconnection"],$query);
}
function inserisci($nome_tabella = NULL){
	mod_db($nome_tabella,'');
}
function aggiorna($nome_tabella = NULL, $condizione = NULL){
	mod_db($nome_tabella,$condizione);
}
function cancella($nome_tabella = NULL, $condizione = NULL){
	$query = "LOCK TABLES ".$nome_tabella." WRITE";
	@ mysqli_query ($GLOBALS["Fconnection"],$query);	
	$query="DELETE FROM ".$nome_tabella." WHERE ".$condizione;
	if (!($result = @ mysqli_query ($GLOBALS["Fconnection"],$query))) $GLOBALS["errore"]='si è verificato un errore';
	$query = "UNLOCK TABLES";
	@ mysqli_query ($GLOBALS["Fconnection"],$query);
}
function leggi_db($nome_tabella = NULL, $condizione=false, $ord=false, $tipoOrd=false){
	$query = "SELECT * FROM ".$nome_tabella;
	if ($condizione) $query .=" WHERE ".$condizione;
	if ($ord) $query .=" ORDER BY ".$ord;
	if ($tipoOrd) $query .=" ".$tipoOrd;
	$result = @ mysqli_query ($GLOBALS["Fconnection"],$query);
	$GLOBALS["record_tot"] = @ mysqli_num_rows($result);
	$GLOBALS["row"] = @ mysqli_fetch_array($result);
	if($GLOBALS["record_tot"]!=0){
		for ($n=0; $n<mysqli_num_fields($result); $n++){
			$GLOBALS["row"][mysqli_field_name($result,$n)]=$GLOBALS["row"][$n];
		}
	}
	//echo $query."<br>";
}
function leggi_db2($nome_tabella = NULL, $condizione=false, $ord=false, $tipoOrd=false){
	$query = "SELECT * FROM ".$nome_tabella;
	if ($condizione) $query .=" WHERE ".$condizione;
	if ($ord) $query .=" ORDER BY ".$ord;
	if ($tipoOrd) $query .=" ".$tipoOrd;
	$result = @ mysqli_query ($GLOBALS["Fconnection"],$query);
	$GLOBALS["record_tot2"] = @ mysqli_num_rows($result);
	$GLOBALS["row2"] = @ mysqli_fetch_array($result);
	if($GLOBALS["record_tot2"]!=0){
		for ($n=0; $n<mysqli_num_fields($result); $n++){
			$GLOBALS["row2"][mysqli_field_name($result,$n)]=$GLOBALS["row2"][$n];
		}
	}
}
function dettaglio_db($nome_tabella = NULL, $condizione = NULL){
	$query = "SELECT * FROM ".$nome_tabella." WHERE ".$condizione;
	$result = @ mysqli_query ($GLOBALS["Fconnection"],$query);
	$record_tot = @ mysqli_num_rows($result);
	$row = @ mysqli_fetch_array($result);
	if($record_tot!=0){
		for ($n=0; $n<mysqli_num_fields($result); $n++){
			$GLOBALS[mysqli_field_name($result,$n)]=$row[$n];
		}
	}
}
function Q($query = NULL){
	if (!($result = @ mysqli_query ($GLOBALS["Fconnection"],$query))) $GLOBALS["errore"]='si è verificato un errore';
}
function elenca_db($nome_tabella = NULL, $condizione=false, $ord=false, $tipoOrd=false){
	unset($GLOBALS["row"]);
	if ($GLOBALS["parolaRicerca"]){
		$condizione='';
		if($condizione) $condizione.=" AND ";		
		$condizione.=" (";
		$campi=explode("|",$GLOBALS["campoRicerca"]);
		for ($k=0; $k<count($campi); $k++){
			if ($k>0) $condizione.=" OR ";
			$condizione.=" ".$campi[$k]." REGEXP '".$GLOBALS["parolaRicerca"]."'";
		}
		$condizione.=")";		
	}
	$query = "SELECT * FROM ".$nome_tabella;
	if ($condizione) $query .=" WHERE ".$condizione;
	$result = @ mysqli_query ($GLOBALS["Fconnection"],$query);
	$GLOBALS["record_tot"] = @ mysqli_num_rows($result);
	if ($ord) $query .=" ORDER BY ".$ord;
	if ($tipoOrd) $query .=" ".$tipoOrd;
	if (!$GLOBALS["record"]) $GLOBALS["record"]=0;
	$query .=" LIMIT ".$GLOBALS["record"].",".$GLOBALS["record_vis"];
	$result = @ mysqli_query ($GLOBALS["Fconnection"],$query);
	$GLOBALS["record_query"] = @ mysqli_num_rows($result);
	if($GLOBALS["record_query"]!=0){
		$f=0;
		while($row2 = @ mysqli_fetch_array($result)){
			for ($n=0; $n<mysqli_num_fields($result); $n++){
				$contenuto=$row2[$n];
				if($GLOBALS["campoRicerca"]==mysqli_field_name($result,$n)) $contenuto=str_replace($GLOBALS["parolaRicerca"],"<u><b>".$GLOBALS["parolaRicerca"]."</b></u>",$contenuto);
				$GLOBALS["row"][$f][mysqli_field_name($result,$n)]=$contenuto;
			}
			$f++;
		}
	}
}
function elenca_db2($nome_tabella = NULL, $condizione=false, $ord=false, $tipoOrd=false){
	unset($GLOBALS["row2"]);
	$query = "SELECT * FROM ".$nome_tabella;
	if ($condizione) $query .=" WHERE ".$condizione;
	$result = @ mysqli_query ($GLOBALS["Fconnection"],$query);
	$GLOBALS["record_tot2"] = @ mysqli_num_rows($result);
	if ($ord) $query .=" ORDER BY ".$ord;
	if ($tipoOrd) $query .=" ".$tipoOrd;
	$result = @ mysqli_query ($GLOBALS["Fconnection"],$query);
	$GLOBALS["record_query2"] = @ mysqli_num_rows($result);
	if($GLOBALS["record_query2"]!=0){
		$f=0;
		while($row2 = @ mysqli_fetch_array($result)){
			for ($n=0; $n<mysqli_num_fields($result); $n++){
				$contenuto=$row2[$n];
				$GLOBALS["row2"][$f][mysqli_field_name($result,$n)]=$contenuto;
			}
			$f++;
		}
	}
}
function mod_vis($nome_tabella = NULL, $condizione = NULL){
	if ($GLOBALS["vis"]){
		if ($GLOBALS["vis"]=="visibile") $Visibile='0';
		if ($GLOBALS["vis"]=="nascosto") $Visibile='1';
		$query = "LOCK TABLES ".$nome_tabella." WRITE";
		@ mysqli_query ($GLOBALS["Fconnection"],$query);
		$query = "UPDATE ".$nome_tabella." SET Visibile='".$Visibile."' WHERE ".$condizione;
		if (!($result = @ mysqli_query ($GLOBALS["Fconnection"],$query))) $GLOBALS["errore"]='si è verificato un errore';
		$query = "UNLOCK TABLES";
		@ mysqli_query ($GLOBALS["Fconnection"],$query);
	}
}
function backup($nome_tabella = NULL){
$GLOBALS["dump"].='#
# Dump dei dati per la tabella '.$nome_tabella.'
#
';	
	$query = "SELECT * FROM ".$nome_tabella;
	$result = @ mysqli_query ($GLOBALS["Fconnection"],$query);
	$record_tot = @ mysqli_num_rows($result);
	if($record_tot!=0){
		while($row = @ mysqli_fetch_array($result)){
			$GLOBALS["dump"].='INSERT INTO '.$nome_tabella.' VALUES (';
			for ($n=0; $n<mysqli_num_fields($result); $n++){
				$type = $result -> fetch_fields()->type;
				if($type=='string' || $type=='blob') $GLOBALS["dump"].="'";
				$GLOBALS["dump"].=addslashes($row[$n]);
				if($type=='string' || $type=='blob') $GLOBALS["dump"].="'";
				if ($n<mysqli_num_fields($result)-1)$GLOBALS["dump"].=", ";
			}
			$GLOBALS["dump"].=');
';
		}
	}
	$GLOBALS["dump"].='


';
}
function export_excel($nome_tabella = NULL){
$GLOBALS["dump"]='';	
	$query = "SELECT * FROM ".$nome_tabella;
	$result = @ mysqli_query ($GLOBALS["Fconnection"],$query);
	$record_tot = @ mysqli_num_rows($result);
	if($record_tot!=0){
		while($row = @ mysqli_fetch_array($result)){
			for ($n=0; $n<mysqli_num_fields($result); $n++){
				$GLOBALS["dump"].=$row[$n];
				if ($n<mysqli_num_fields($result)-1)$GLOBALS["dump"].=";";
			}
			$GLOBALS["dump"].=chr(10);
		}
	}
	$addDump='';
	for ($n=0; $n<mysqli_num_fields($result); $n++){
		if ($n<mysqli_num_fields($result)-1) $addDump.='*'.mysqli_field_name($result,$n).";";
	}
	$addDump.=chr(10);
	$GLOBALS["dump"]=$addDump.$GLOBALS["dump"];
}
function invia_email($mail_to = NULL, $mail_subject = NULL, $messaggio = NULL, $mail_from = NULL, $formato = NULL, $userfile = NULL, $filename=false){
	$mail_boundary=md5(uniqid(time()));
	$mail_headers="From: ".$mail_from."\r\n";
	$mail_headers.="MIME-Version: 1.0\r\n";
	if(!$userfile){
		if(strtolower($formato)=='html')$mail_headers.="Content-Type: text/html; charset=utf-8\n";
		else $mail_headers.="Content-type: text/plain; charset=utf-8\r\n\r\n";
	}else{
		$mail_headers.="Content-type: multipart/mixed; boundary=\"".$mail_boundary."\"\r\n";
		$mail_body = "--".$mail_boundary."\n";
		if(strtolower($formato)=='html')$mail_body.="Content-Type: text/html; charset=utf-8\n";
		else $mail_body.="Content-type: text/plain; charset=utf-8\r\n\r\n";
	}
	$mail_body .= $messaggio."\n\n";
	if($userfile){
		$mail_body .= "--".$mail_boundary."\n";
		$file=leggi_file($userfile);
		$file=chunk_split(base64_encode($file));
		if(!$filename)$filename=basename($userfile);
		$estensione=strrchr($userfile,".");
		$estensione=strtolower(substr($estensione,1,strlen($estensione)-1));
		if($estensione=='gif' || $estensione=='jpg' || $estensione=='jpe' || $estensione=='tif' || $estensione=='eps' || $estensione=='jpeg' || $estensione=='png' || $estensione=='tiff' || $estensione=='ai' || $estensione=='pdf') $tipo_file="image/".$estensione;
		if($estensione=='txt') $tipo_file="text/plan";
		if($estensione=='doc' || $estensione=='rtf') $tipo_file="application/msword";
		if($tipo_file=='') $tipo_file="application/octet-stream";
		$mail_body.="Content-type: ".$tipo_file."; name=".$filename."\r\n";
		$mail_body.="Content-transfer-encoding: base64\r\n\r\n";
		$mail_body.=$file."\r\n\r\n";
		$mail_body.="--".$mail_boundary."\r\n";
	}
	

	if($GLOBALS["EmailSito"]) mail($mail_to,$mail_subject,$mail_body,$mail_headers);
	else{
		//$contenuto_email=$mail_headers.$mail_body;
		//salva_fiAle("allegati/mail.msg",$contenuto_email);
	}
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