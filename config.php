<?php

ini_set('error_reporting', E_ALL);

$APIurl = "http://api.iRail.be/";
//Link to iRail API
$iRailAgent = "InfoScreen v0.1";

$systems = array("NMBS", "MIVB");

$timeout = 60;

// DATABASE
$id = 1; // should be a fixed value somewhere

$db = new SQLite3(ini_get('include_path') . 'customers.db');
$row = $db->querySingle('SELECT * FROM infoscreen WHERE id = ' . $id, true);

$template = $row['template'];
$vicinity = $row['vicinity'];
$companylogo = $row['companylogo'];
$motd = $row['motd'];

$refreshinterval = $row['refreshinterval'];
$cycleinterval = $row['cycleinterval'];

$rowstoshow = $row['rowstoshow'];

$nmbs = explode(';', $row['nmbs']);
for ($i = 0; $i < sizeof($nmbs); $i++) {
  $nmbs[$i] = str_replace('"', '', $nmbs[$i]);
}

$mivb = explode(';', $row['mivb']);
for ($i = 0; $i < sizeof($mivb); $i++) {
  $mivb[$i] = str_replace('"', '', $mivb[$i]);
}

$latitude = $row['lat'];
$longitude = $row['long'];

?>
