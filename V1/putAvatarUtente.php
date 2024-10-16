<?php
include_once("_include/_testata.php");
function getimagepng($base64_string = NULL){
	$im = base64_decode($base64_string);
	$filename = "/tmp/";
	$filename .= uniqid("intrinsic_").".png";

	$file = fopen($filename, "wb");
	$newIamge = resize_image($im, 500, 500);

	fwrite($file, $newIamge);
	fclose($file);
	return $filename;
}

function resize_image($file = NULL, $w = NULL, $h = NULL, $crop=FALSE) {
	//list($width, $height) = getimagesize($file);
	$src = imagecreatefromstring($file);
	if (!$src) return "";
	$width = imagesx($src);
	$height = imagesy($src);
	
	$r = $width / $height;
	if ($crop) {
		if ($width > $height) {
			$width = ceil($width-($width*abs($r-$w/$h)));
		} else {
			$height = ceil($height-($height*abs($r-$w/$h)));
		}
		$newwidth = $w;
		$newheight = $h;
	} else {
		if ($w/$h > $r) {
			$newwidth = $h*$r;
			$newheight = $h;
		} else {
			$newheight = $w/$r;
			$newwidth = $w;
		}
	}
	
	//$src = imagecreatefrompng($file);
	$dst = imagecreatetruecolor($newwidth, $newheight);
	imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
	
	// Buffering
	ob_start();
	imagepng($dst);
	$data = ob_get_contents();
	ob_end_clean();
	return $data;
}


if($b64)$JSNPOST=base64_decode($JSNPOST);
else $JSNPOST=conv($JSNPOST);
$vars=json_decode($JSNPOST);
$idUtente=$vars->idUtente;
$imgAvatar = $vars->imgAvatar;
$logoAzienda = $vars->logoAzienda;
	
$cartellaUtente = "../__files_utenti/files/".$idUtente."/";
if(!file_exists($cartellaUtente))mkdir($cartellaUtente);


$fileAvatarJPG = $cartellaUtente."__avatar.jpg";
$fileAvatarMicroJPG = $cartellaUtente."__avatarMicro.jpg";

if($imgAvatar){
	$IMG = base64_decode(str_replace("data:image/jpeg;base64,","",$imgAvatar));
	file_put_contents("../__temp/img.json", str_replace("data:image/jpeg;base64,","",$imgAvatar));
	file_put_contents($fileAvatarJPG, $IMG);
	$avatarMicro = resize_image($IMG, 20, 20);
	if($avatarMicro){
		file_put_contents($fileAvatarMicroJPG, $avatarMicro);
	}
	
}else{
	if(file_exists($fileAvatarJPG))unlink($fileAvatarJPG);
	if(file_exists($fileAvatarMicroJPG))unlink($fileAvatarMicroJPG);
}

$fileLogoJPG = $cartellaUtente."__logo.jpg";
$fileLogoMicroJPG = $cartellaUtente."__logoMicro.jpg";

if($logoAzienda){
	$IMG = base64_decode(str_replace("data:image/jpeg;base64,","",$logoAzienda));
	file_put_contents($fileLogoJPG, $IMG);
	$logoMicro = resize_image($IMG, 20, 20);
	if($logoMicro){
		file_put_contents($fileLogoMicroJPG, $logoMicro);
	}
	
}else{
	if(file_exists($fileLogoJPG))unlink($fileLogoJPG);
	if(file_exists($fileLogoMicroJPG))unlink($fileLogoMicroJPG);
}
			
?>