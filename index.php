<?php

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$ranstring = generateRandomString();


$ap_lat = $_GET['aplat']; // -38.238588


$ap_lng = $_GET['aplng']; // 146.416571


$ap_height = $_GET['apheight']; // 4


$ap_antennagain = $_GET['apgain']; // 25


$ap_devicename = $_GET['apdevicename']; // LiteBeam+5AC



$cpe_lat = $_GET['cpelat']; // -38.285928178884


$cpe_lng = $_GET['cpelng']; // 146.50724543862


$cpe_height = $_GET['cpeheight']; // 4


$cpe_antennagain = $_GET['cpegain']; // 25



$channelwidth = $_GET['channelwidth']; // 40


$eirp = $_GET['eirp']; // 50


$frequency = $_GET['frequency']; // 5600



$airlinkurl = "https://link.ui.com/#ap.location.lat=$ap_lat&ap.location.lng=$ap_lng&ap.device.antennaGain=$ap_antennagain&ap.device.channelWidth=$channelwidth&ap.device.eirp=$eirp&ap.device.frequency=$frequency&ap.device.name=$ap_devicename&ap.height=$ap_height&coverageCpeHeight=$cpe_height&coverageRadius=20000&mapTypeId=hybrid&version=1.0.2&cpeList.0.location.lat=$cpe_lat&cpeList.0.location.lng=$cpe_lng&cpeList.0.device.antennaGain=$cpe_antennagain&cpeList.0.device.eirp=$eirp&cpeList.0.height=$cpe_height&ranstring=$ranstring";



exec("google-chrome --headless --virtual-time-budget=10000 --no-sandbox --disable-gpu --dump-dom '$airlinkurl' > uidownload'$ranstring'.html");






require 'simple_html_dom.php';

$html = file_get_html('uidownload'. $ranstring .'.html');
$cpe_upload = substr($html->find('div.linkResultsHeader__capacity',0), 73, -6);
$cpe_download = substr($html->find('div.linkResultsHeader__capacity',1), 74, -6);

$ap_connection = substr($html->find('div.percentPieChart__value',0), 36, -6);
$cpe_connection = substr($html->find('div.percentPieChart__value',1), 36, -6);

$connection_distance = substr($html->find('span.linkResultsHeader__linkDistanceValue',0), 51, -7);
$connection_distance_unit = substr($html->find('span.linkResultsHeader__linkDistanceUnit',0), 50, -7);

$estimated_dbm = substr($html->find('span.linkPerformanceCharts__value',0), 44, -7);



header('Content-Type: application/json');
if($ap_connection === '0 %' AND $cpe_connection === '0 %'){
  echo json_encode(array(
      "status" => "link obstructed",
      "airlink_url" => "$airlinkurl",
  ));
}else{
echo json_encode(array(
    "status" => "ok",
    "cpe_upload" => "$cpe_upload",
    "cpe_download" => "$cpe_download",
    "ap_connection" => "$ap_connection",
    "cpe_connection" => "$cpe_connection",
    "connection_distance" => "$connection_distance",
    "connection_unit" => "$connection_distance_unit",
    "estimated_dBm" => "$estimated_dbm",
    "airlink_url" => "$airlinkurl",
));
}

exec("rm uidownload'$ranstring'.html");


?>
