<?php
error_reporting(E_WARNING);
ini_set('display_errors', 1);

include_once('../../_include/funzioni.php');

$json = json_decode(file_get_contents("https://www.iaomai.it/___API/url/getProdsForIaomai.php"),true);

file_put_contents("../../_include/db/prodotti.json",json_encode($json["prodotti"], JSON_UNESCAPED_UNICODE));
file_put_contents("../../_include/db/arr_mappe.json",json_encode($json["arr_mappe"], JSON_UNESCAPED_UNICODE));
file_put_contents("../../_include/db/ratings.json",json_encode($json["ratings"], JSON_UNESCAPED_UNICODE));
file_put_contents("../../_include/db/global_rate.json",json_encode($json["global_rate"], JSON_UNESCAPED_UNICODE));
file_put_contents("../../_include/db/paesi.json",json_encode($json["paesi"], JSON_UNESCAPED_UNICODE));
file_put_contents("../../_include/db/lingue.json",json_encode($json["lingue"], JSON_UNESCAPED_UNICODE));
file_put_contents("../../_include/db/promo.json",json_encode($json["promo"], JSON_UNESCAPED_UNICODE));

?>