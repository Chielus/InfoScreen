<?php

$APIurl = "http://api.iRail.be/";
//Link to iRail API
$iRailAgent = "InfoScreen v0.1";

$template = "default";
$vicinity = 0.2;	//stations closer than 200m

$systems = array("NMBS", "MIVB");

$timeout = 60;

$mivbarray = array(array("name" =>"Steenbok", "distance" => 186),
		   // array("name" =>"Plejade", "distance" => "362"),
		   //array("name" =>"Lombaerde", "distance" => "368"),
		   array("name" =>"Carina", "distance" => 425),
		   array("name" =>"Paduwa", "distance" => 424));

$nmbsarray = array(array("name" =>"Brussel Noord", "distance" => 3900),
		   array("name" =>"Evere", "distance" => 3900),
		   array("name" =>"Meiser", "distance" => 1300));

//3000m/hour = 50m/minute
for($i = 0; $i < sizeof($mivbarray); $i++){
     $mivbarray[$i]["walking"] = round($mivbarray[$i]["distance"]/50);
}
for($i = 0; $i < sizeof($nmbsarray); $i++){
     $nmbsarray[$i]["walking"] = round($nmbsarray[$i]["distance"]/50);
}

?>
