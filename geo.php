<?php

$ipAddress = $_SERVER['REMOTE_ADDR'];

$ch = curl_init('http://ipwhois.app/json/192.168.0.145');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$jsonData = curl_exec($ch);
curl_close($ch);

$resultData = json_decode($jsonData, true);
var_dump($resultData);

?>